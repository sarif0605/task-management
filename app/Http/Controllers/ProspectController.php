<?php

namespace App\Http\Controllers;

use App\Http\Requests\Prospect\ProspectCreateRequest;
use App\Http\Requests\Prospect\ProspectUpdateRequest;
use App\Models\Prospect;

class ProspectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prospect = Prospect::orderBy('created_at', 'DESC')->get();
        return view('prospects.index', compact('prospect'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('advertising.marketing.prospect.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProspectCreateRequest $request)
    {
        $data = $request->validated();
        $prospect = new Prospect($data);
        $prospect->save();
        return redirect()->route('prospects.index')->with('success', 'Prospect created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $prospect = Prospect::find($id);
        if (!$prospect) {
            return redirect()->route('prospects.index')
            ->with('error', 'Prospect dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('prospects.show', compact('prospect'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $prospect = Prospect::find($id);
        if (!$prospect) {
            return redirect()->route('prospects.index')
            ->with('error', 'Prospect dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('advertising.marketing.prospect.edit', compact('prospect'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProspectUpdateRequest $request, string $id)
    {
        $prospect = Prospect::find($id);
        if (!$prospect) {
            return redirect()->route('prospects.index')
            ->with('error', 'Prospect dengan ID ' . $id . ' tidak ditemukan.');
        }
        $data = $request->validated();
        $prospect->update($data);
        return redirect()->route('prospects.index')->with('success', 'Prospect updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $prospect = Prospect::find($id);
        if (!$prospect) {
            return redirect()->route('prospects.index')
            ->with('error', 'Prospect dengan ID ' . $id . ' tidak ditemukan.');
        }
        $prospect->delete();
        return redirect()->route('prospects.index')->with('success', 'Prospect deleted successfully.');
    }
}
