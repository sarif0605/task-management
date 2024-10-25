<?php

namespace App\Http\Controllers;

use App\Http\Requests\Survey\SurveyCreateRequest;
use App\Http\Requests\Survey\SurveyUpdateRequest;
use App\Models\Prospect;
use App\Models\Survey;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $survey = Survey::with('prospect')->get();
        return view('advertising.sales.survey.index', compact('survey'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $prospect = Prospect::all();
        return view('advertising.sales.survey.create', compact('prospect'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SurveyCreateRequest $request)
    {
        $data = $request->validated();
        $survey = new Survey($data);
        $survey->save();
        return redirect()->route('advertising.sales.survey.index')->with('success', 'Survey created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $survey = Survey::with('prospect')->find($id);
        if (!$survey) {
            return redirect()->route('advertising.sales.survey.index')
            ->with('error', 'Survey dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('advertising.sales.survey.show', compact('prospect'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $survey = Survey::find($id);
        $prospect = Prospect::all();
        if (!$survey) {
            return redirect()->route('advertising.sales.survey.index')
            ->with('error', 'Survey dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('advertising.sales.survey.edit', compact('survey', 'prospect'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SurveyUpdateRequest $request, string $id)
    {
        $survey = Survey::find($id);
        if (!$survey) {
            return redirect()->route('advertising.sales.survey.index')
            ->with('error', 'Survey dengan ID ' . $id . ' tidak ditemukan.');
        }
        $data = $request->validated();
        $survey->update($data);
        return redirect()->route('advertising.sales.survey.index')->with('success', 'Survey updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $survey = Survey::find($id);
        if (!$survey) {
            return redirect()->route('advertising.sales.survey.index')
            ->with('error', 'Survey dengan ID ' . $id . ' tidak ditemukan.');
        }
        $survey->delete();
        return redirect()->route('advertising.sales.survey.index')->with('success', 'Survey deleted successfully.');
    }
}
