<?php

namespace App\Http\Controllers\Constructor;

use App\Http\Requests\Survey\SurveyCreateRequest;
use App\Http\Requests\Survey\SurveyUpdateRequest;
use App\Models\Prospect;
use Illuminate\Support\Facades\File;
use App\Models\Survey;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SurveyImages;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SurveyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['verified', 'isStatusAccount'])->only('store', 'create', 'edit', 'update', 'destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $survey = Survey::with('prospect', 'survey_images')->orderBy('created_at', 'DESC')->get();
            return response()->json(['data' => $survey]);
        }
        return view('contractor.survey.index');
    }

    public function store(SurveyCreateRequest $request)
    {
        $data = $request->validated();
        $survey = new Survey($data);
        $survey->prospect_id = $data['prospect_id'];
        $survey->save();
        if (isset($data['prospect_id'])) {
            $prospect = Prospect::find($data['prospect_id']);
            if ($prospect) {
                $prospect->status = 'survey';
                $prospect->save();
            }
        }
        return response()->json(['message' => 'Survey created successfully.'], 200);
    }

    /**
     * Display the specified resource.
     */

    public function show(string $id)
    {
        $survey = Survey::with('prospect')->find($id);
        if (!$survey) {
            return redirect()->route('surveys')
            ->with('error', 'Survey dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('contractor.survey.show', compact('survey'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $survey = Survey::find($id);
        $prospect = Prospect::all();
        if (!$survey) {
            return redirect()->route('surveys.edit')
            ->with('error', 'Survey dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('contractor.survey.edit', compact('survey', 'prospect'));
    }

public function update(SurveyUpdateRequest $request, string $id)
{
    $survey = Survey::find($id);
    if (!$survey) {
        return response()->json([
            'error' => 'Survey tidak ditemukan.'
        ], 404);
    }

    try {
        DB::beginTransaction();
        $data = $request->validated();
        $survey->update($data);

        if ($survey->prospect_id) {
            $prospect = Prospect::find($survey->prospect_id);
            if ($prospect) {
                $prospect->status = 'survey';
                $prospect->save();
            }
        }
        // Handle new images
        if ($request->hasFile('image')) {
            $existingImages = SurveyImages::where('survey_id', $survey->id)->get();
            foreach ($existingImages as $existingImage) {
                Storage::disk('local')->delete('public/survey/' . $existingImage->image_url);
                $existingImage->delete();
            }
            foreach ($request->file('image') as $image) {
                $imageContent = file_get_contents($image->getRealPath());
                $imageName = uniqid() . '_' . $image->getClientOriginalName();
                Storage::disk('local')->put("public/survey/{$imageName}", $imageContent);
                SurveyImages::create([
                    'survey_id' => $survey->id,
                    'image_url' => $imageName,
                ]);
            }
        }
        DB::commit();
        return redirect()->route('surveys');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('surveys');
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $survey = Survey::find($id);
        if (!$survey) {
            return response()->json(['message' => 'Survey not found'], 404);
        }
        try {
            DB::beginTransaction();
            foreach ($survey->images as $image) {
                if (!empty($image)) {
                    Storage::disk('local')->delete('public/survey/' . $image);
                }
                $image->delete();
            }
            $survey->delete();
            DB::commit();
            return response()->json(['message' => 'Survey deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Survey deletion error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete survey: ' . $e->getMessage()], 500);
        }
    }
}
