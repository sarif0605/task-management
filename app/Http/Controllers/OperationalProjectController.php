<?php

namespace App\Http\Controllers;

use App\Http\Requests\OperationalProject\OperationalProjectCreateRequest;
use App\Http\Requests\OperationalProject\OperationalProjectUpdateRequest;
use App\Models\DealProject;
use App\Models\OperationalProjects;
use Illuminate\Http\Request;

class OperationalProjectController extends Controller
{
    public function index()
    {
        $operationalProject = OperationalProjects::with('deal_project')->orderBy('created_at', 'DESC')->get();
        return view('contractor.operational_project.index', compact('operationalProject'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dealProject = DealProject::all();
        return view('contractor.operational_project.create', compact('dealProject'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OperationalProjectCreateRequest $request)
    {
        $data = $request->validated();
        $operationalProject = new OperationalProjects($data);
        $operationalProject->save();
        return redirect()->route('contractor.operational_project.index')->with('success', 'Survey created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $operationalProject = OperationalProjects::with('deal_project')->find($id);
        if (!$operationalProject) {
            return redirect()->route('contractor.operational_project.index')
            ->with('error', 'Operational Project dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('contractor.operational_project.show', compact('operationalProject'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $operationalProject = OperationalProjects::find($id);
        $dealProject = DealProject::all();
        if (!$operationalProject) {
            return redirect()->route('contractor.operational_project.index')
            ->with('error', 'Operational Project dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('contractor.operational_project.edit', compact('operationalProject', 'dealProject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OperationalProjectUpdateRequest $request, string $id)
    {
        $operationalProject = OperationalProjects::find($id);
        if (!$operationalProject) {
            return redirect()->route('contractor.operational_project.index')
            ->with('error', 'Operational Project dengan ID ' . $id . ' tidak ditemukan.');
        }
        $data = $request->validated();
        $operationalProject->update($data);
        return redirect()->route('contractor.operational_project.index')->with('success', 'Survey updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $operationalProject = OperationalProjects::find($id);
        if (!$operationalProject) {
            return redirect()->route('contractor.operational_project.index')
            ->with('error', 'Survey dengan ID ' . $id . ' tidak ditemukan.');
        }
        $operationalProject->delete();
        return redirect()->route('contractor.operational_project.index')->with('success', 'Survey deleted successfully.');
    }
}
