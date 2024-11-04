<?php

namespace App\Http\Controllers\Constructor;

use App\Http\Requests\Survey\SurveyCreateRequest;
use App\Http\Requests\Survey\SurveyUpdateRequest;
use App\Models\Prospect;
use App\Models\Survey;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SurveyImages;

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
    public function create()
    {
        $prospect = Prospect::all();
        return view('contractor.survey.create', compact('prospect'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(SurveyCreateRequest $request)
    {
        try {
            $data = $request->validated();
            $survey = new Survey($data);
            $survey->save();
            if ($request->hasFile('images')) {
                $imageLinks = [];
                foreach ($request->file('images') as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->storeAs('uploads', $filename, 'public');
                    $imageLinks[] = $imagePath;
                    SurveyImages::create([
                        'survey_id' => $survey->id,
                        'image_link' => $imagePath,
                    ]);
                }
            }

            if (isset($data['prospect_id'])) {
                $prospect = Prospect::find($data['prospect_id']);
                if ($prospect) {
                    $prospect->status = 'survey';
                    $prospect->save();
                } else {
                    return response()->json(['error' => 'Prospect not found.'], 404);
                }
            }

            return response()->json(['message' => 'Survey created successfully and Prospect status updated.'], 200);
        } catch (\Exception $e) {
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
            return redirect()->route('contractor.survey.index')
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
            return redirect()->route('contractor.survey.index')
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
            return redirect()->route('contractor.survey.index')
            ->with('error', 'Survey dengan ID ' . $id . ' tidak ditemukan.');
        }
        $data = $request->validated();
        $survey->update($data);
        return redirect()->route('contractor.survey.index')->with('success', 'Survey updated successfully.');
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
