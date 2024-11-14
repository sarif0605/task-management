<?php

namespace App\Http\Controllers\Constructor;

use App\Http\Requests\Survey\SurveyCreateRequest;
use App\Http\Requests\Survey\SurveyUpdateRequest;
use App\Models\Prospect;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Survey;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SurveyImages;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            $survey = Survey::with('prospect')->orderBy('created_at', 'DESC')->get();
            return response()->json(['data' => $survey]);
        }
        return view('contractor.survey.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($prospect_id)
    {
        return view('contractor.survey.create', compact('prospect_id'));
    }

    public function store(SurveyCreateRequest $request)
    {
        try {
            DB::beginTransaction();
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

            // Handle multiple image uploads
            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $image) {
                    $cloudinaryImage = $image->storeOnCloudinary('bnp');
                    SurveyImages::create([
                        'survey_id' => $survey->id,
                        'image_url' => $cloudinaryImage->getSecurePath(),
                        'image_public_id' => $cloudinaryImage->getPublicId()
                    ]);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Survey created successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Survey creation error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create survey: ' . $e->getMessage()], 500);
        }
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

    /**
     * Update the specified resource in storage.
     */
    public function update(SurveyUpdateRequest $request, string $id)
    {
        $survey = Survey::find($id);
        if (!$survey) {
            return redirect()->route('surveys.edit')
            ->with('error', 'Survey dengan ID ' . $id . ' tidak ditemukan.');
        }
        $data = $request->validated();
        $survey->update($data);
        return redirect()->route('surveys')->with('success', 'Survey updated successfully.');
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
        $survey->delete();
        return response()->json(['message' => 'Survey deleted successfully'], 200);
    }
}
