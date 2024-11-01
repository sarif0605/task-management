<?php

namespace App\Http\Controllers\Constructor;

use App\Http\Requests\Material\MaterialCreateRequest;
use App\Models\Materials;
use App\Http\Controllers\Controller;
use App\Models\DealProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();
            $query = Materials::with(['deal_project.prospect', 'deal_project.deal_project_users']);
            if ($user->position === 'pengawas') {
                $query->whereHas('deal_project.deal_project_users', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }
            $materials = $query->orderBy('created_at', 'DESC')->get();

            return response()->json(['data' => $materials]);
        }

        return view('contractor.material.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $operationalProject = DealProject::all();
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
        $operationalProject = DealProject::all();
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
