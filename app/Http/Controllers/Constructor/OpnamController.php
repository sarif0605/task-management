<?php

namespace App\Http\Controllers\Constructor;

use App\Http\Requests\Opnam\OpnamCreateRequest;
use App\Http\Requests\Opnam\OpnamUpdateRequest;
use App\Models\OperationalProjects;
use App\Models\Opnams;
use App\Http\Controllers\Controller;
use App\Models\DealProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpnamController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();
            $query = Opnams::with(['deal_project.prospect', 'deal_project.deal_project_users']);
            if ($user->position === 'pengawas') {
                $query->whereHas('deal_project.deal_project_users', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }
            $materials = $query->orderBy('created_at', 'DESC')->get();
            return response()->json(['data' => $materials]);
        }
        return view('contractor.opnam.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $operationalProject = DealProject::all();
        return view('contractor.opnam.create', compact('operationalProject'));
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
        $operationalProject = DealProject::all();
        if (!$opnam) {
            return redirect()->route('contractor')
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
