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
        $this->middleware(['isVerificationAccount', 'isStatusAccount'])->only('store', 'create', 'edit', 'update', 'destroy');
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

     public function show($id) {
        $survey = Survey::with('prospect', 'survey_images')->find($id);
        if (!$survey) {
            return response()->json(['error' => 'Survey not found'], 404);
        }
        return response()->json([
            'survey' => $survey
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $survey = Survey::with('survey_images')->find($id);
        if (!$survey) {
            return response()->json([
                'error' => 'Survey not found'
            ], 404);
        }
        return response()->json([
            'survey' => $survey
        ]);
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
        if ($request->has('deleted_images')) {
            $deletedImages = json_decode($request->deleted_images);
            foreach ($deletedImages as $imageId) {
                $image = SurveyImages::find($imageId);
                if ($image) {
                    Storage::disk('local')->delete($image->image_url);
                    $image->delete();
                }
            }
        }

        // Handle new images
        if ($request->hasFile('image')) {
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
        return response()->json([
            'message' => 'Survey updated successfully.'
        ], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'error' => 'Failed to update survey: ' . $e->getMessage()
        ], 500);
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
                $imagePath = str_replace('/storage', 'public', $image->image_url);
                if (Storage::exists($imagePath)) {
                    Storage::delete($imagePath);
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
