<?php

namespace App\Http\Controllers\Constructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportProject\ReportProjectCreateRequest;
use App\Http\Requests\ReportProject\ReportProjectUpdateRequest;
use App\Models\DealProject;
use App\Models\ReportProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware(['isVerificationAccount', 'isStatusAccount'])->only('store', 'create', 'edit', 'update', 'destroy');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();
            $query = ReportProject::with(['deal_project.prospect', 'deal_project.deal_project_users']);
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
    public function store(ReportProjectCreateRequest $request)
    {
        $data = $request->validated();
        $material = new ReportProject($data);
        $material->save();
        return redirect()->route('contractor.material.index')->with('success', 'Material created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $material = ReportProject::with('operational_project')->find($id);
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
        $material = ReportProject::find($id);
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
    public function update(ReportProjectUpdateRequest $request, string $id)
    {
        $material = ReportProject::find($id);
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
        $material = ReportProject::find($id);
        if (!$material) {
            return redirect()->route('contractor.material.index')
            ->with('error', 'Material dengan ID ' . $id . ' tidak ditemukan.');
        }
        $material->delete();
        return redirect()->route('contractor.material.index')->with('success', 'Material deleted successfully.');
    }
}
