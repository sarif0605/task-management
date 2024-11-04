<?php

namespace App\Http\Controllers\Constructor;

use App\Http\Requests\Constraint\ConstraintCreateRequest;
use App\Http\Requests\Constraint\ConstraintUpdateRequest;
use App\Models\Constraints;
use App\Models\OperationalProjects;
use App\Http\Controllers\Controller;
use App\Models\DealProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConstraintController extends Controller
{

    public function __construct()
    {
        $this->middleware(['isVerificationAccount', 'isStatusAccount'])->only('store', 'create', 'edit', 'update', 'destroy');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();
            $query = Constraints::with(['deal_project.prospect', 'deal_project.deal_project_users']);
            if ($user->position === 'pengawas') {
                $query->whereHas('deal_project.deal_project_users', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }
            $materials = $query->orderBy('created_at', 'DESC')->get();
            return response()->json(['data' => $materials]);
        }
        return view('contractor.constraint.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $operationalProject = DealProject::all();
        return view('contractor.contraint.create', compact('operationalProject'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConstraintCreateRequest $request)
    {
        $data = $request->validated();
        $constraint = new Constraints($data);
        $constraint->save();
        return redirect()->route('contractor.contraint.index')->with('success', 'Constraint created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $constraint = Constraints::with('operational_project')->find($id);
        if (!$constraint) {
            return redirect()->route('contractor.contraint.index')
            ->with('error', 'Constraint dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('contractor.contraint.show', compact('prospect'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $constraint = Constraints::find($id);
        $operationalProject = DealProject::all();
        if (!$constraint) {
            return redirect()->route('contractor.contraint.index')
            ->with('error', 'Constraint dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('contractor.contraint.edit', compact('material', 'operationalProject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ConstraintUpdateRequest $request, string $id)
    {
        $constraint = Constraints::find($id);
        if (!$constraint) {
            return redirect()->route('contractor.contraint.index')
            ->with('error', 'Constraint dengan ID ' . $id . ' tidak ditemukan.');
        }
        $data = $request->validated();
        $constraint->update($data);
        return redirect()->route('contractor.contraint.index')->with('success', 'Constraint updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $constraint = Constraints::find($id);
        if (!$constraint) {
            return redirect()->route('contractor.contraint.index')
            ->with('error', 'Constraint dengan ID ' . $id . ' tidak ditemukan.');
        }
        $constraint->delete();
        return redirect()->route('contractor.contraint.index')->with('success', 'Constraint deleted successfully.');
    }
}
