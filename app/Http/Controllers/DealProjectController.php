<?php

namespace App\Http\Controllers;

use App\Http\Requests\DealProject\DealProjectCreateRequest;
use App\Http\Requests\DealProject\DealProjectUpdateRequest;
use App\Models\DealProject;
use App\Models\Prospect;

class DealProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dealProject = DealProject::with('prospect')->get();
        return view('advertising.sales.deal_project.index', compact('dealProject'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $prospect = Prospect::all();
        return view('advertising.sales.deal_project.create', compact('prospect'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DealProjectCreateRequest $request)
    {
        $data = $request->validated();
        $dealProject = new DealProject($data);
        $dealProject->save();
        return redirect()->route('advertising.sales.deal_project.index')->with('success', 'Deal Project created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dealProject = DealProject::with('prospect')->find($id);
        if (!$dealProject) {
            return redirect()->route('advertising.sales.deal_project.index')
            ->with('error', 'Deal Project dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('advertising.sales.deal_project.show', compact('dealProject'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dealProject = DealProject::find($id);
        $prospect = Prospect::all();
        if (!$dealProject) {
            return redirect()->route('advertising.sales.deal_project.index')
            ->with('error', 'Deal Project dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('deal_projects.edit', compact('dealProject', 'prospect'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DealProjectUpdateRequest $request, string $id)
    {
        $dealProject = DealProject::find($id);
        if (!$dealProject) {
            return redirect()->route('advertising.sales.deal_project.index')
            ->with('error', 'Deal Project dengan ID ' . $id . ' tidak ditemukan.');
        }
        $data = $request->validated();
        $dealProject->update($data);
        return redirect()->route('advertising.sales.deal_project.index')->with('success', 'Deal Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dealProject = DealProject::find($id);
        if (!$dealProject) {
            return redirect()->route('advertising.sales.deal_project.index')
            ->with('error', 'Survey dengan ID ' . $id . ' tidak ditemukan.');
        }
        $dealProject->delete();
        return redirect()->route('advertising.sales.deal_project.index')->with('success', 'Deal Project deleted successfully.');
    }
}
