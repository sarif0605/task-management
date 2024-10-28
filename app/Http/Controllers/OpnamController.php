<?php

namespace App\Http\Controllers;

use App\Http\Requests\Opnam\OpnamCreateRequest;
use App\Http\Requests\Opnam\OpnamUpdateRequest;
use App\Models\OperationalProjects;
use App\Models\Opnams;
use Illuminate\Http\Request;

class OpnamController extends Controller
{
    public function index()
    {
        $opnam = Opnams::with('operational_project')->orderBy('created_at', 'DESC')->get();
        return view('contractor.opnam.index', compact('material'));
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
    public function store(OpnamCreateRequest $request)
    {
        $data = $request->validated();
        $opnam = new Opnams($data);
        $opnam->save();
        return redirect()->route('contractor.opnam.index')->with('success', 'Material created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $opnam = Opnams::with('operational_project')->find($id);
        if (!$opnam) {
            return redirect()->route('contractor.opnam.index')
            ->with('error', 'Opnam dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('contractor.opnam.show', compact('prospect'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $opnam = Opnams::find($id);
        $operationalProject = OperationalProjects::all();
        if (!$opnam) {
            return redirect()->route('contractor.opnam.index')
            ->with('error', 'Opnam dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('contractor.opnam.edit', compact('material', 'operationalProject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OpnamUpdateRequest $request, string $id)
    {
        $opnam = Opnams::find($id);
        if (!$opnam) {
            return redirect()->route('contractor.opnam.index')
            ->with('error', 'Opnam dengan ID ' . $id . ' tidak ditemukan.');
        }
        $data = $request->validated();
        $opnam->update($data);
        return redirect()->route('contractor.opnam.index')->with('success', 'Opnam updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $opnam = Opnams::find($id);
        if (!$opnam) {
            return redirect()->route('contractor.opnam.index')
            ->with('error', 'Opnam dengan ID ' . $id . ' tidak ditemukan.');
        }
        $opnam->delete();
        return redirect()->route('contractor.opnam.index')->with('success', 'Material deleted successfully.');
    }
}
