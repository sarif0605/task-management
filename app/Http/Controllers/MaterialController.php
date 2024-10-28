<?php

namespace App\Http\Controllers;

use App\Http\Requests\Material\MaterialCreateRequest;
use App\Models\Materials;
use App\Models\OperationalProjects;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        $material = Materials::with('operational_project')->orderBy('created_at', 'DESC')->get();
        return view('contractor.material.index', compact('material'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $operationalProject = OperationalProjects::all();
        return view('contractor.material.create', compact('operationalProject'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MaterialCreateRequest $request)
    {
        $data = $request->validated();
        $material = new Materials($data);
        $material->save();
        return redirect()->route('contractor.material.index')->with('success', 'Material created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $material = Materials::with('operational_project')->find($id);
        if (!$material) {
            return redirect()->route('contractor.material.index')
            ->with('error', 'Material dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('contractor.material.show', compact('prospect'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $material = Materials::find($id);
        $operationalProject = OperationalProjects::all();
        if (!$material) {
            return redirect()->route('contractor.material.index')
            ->with('error', 'Material dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('contractor.material.edit', compact('material', 'operationalProject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MaterialCreateRequest $request, string $id)
    {
        $material = Materials::find($id);
        if (!$material) {
            return redirect()->route('contractor.material.index')
            ->with('error', 'Material dengan ID ' . $id . ' tidak ditemukan.');
        }
        $data = $request->validated();
        $material->update($data);
        return redirect()->route('contractor.material.index')->with('success', 'Material updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $material = Materials::find($id);
        if (!$material) {
            return redirect()->route('contractor.material.index')
            ->with('error', 'Material dengan ID ' . $id . ' tidak ditemukan.');
        }
        $material->delete();
        return redirect()->route('contractor.material.index')->with('success', 'Material deleted successfully.');
    }
}
