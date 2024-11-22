<?php

namespace App\Http\Controllers\Constructor;

use App\Http\Requests\Constraint\ConstraintCreateRequest;
use App\Http\Requests\Constraint\ConstraintUpdateRequest;
use App\Models\Constraints;
use App\Http\Controllers\Controller;
use App\Models\DealProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConstraintController extends Controller
{

    public function __construct()
    {
        $this->middleware(['verified', 'isStatusAccount'])->only('store', 'create', 'edit', 'update', 'destroy');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();
            $query = Constraints::with([
                'report_project.deal_project.prospect',
                'report_project.deal_project.deal_project_users'
            ]);
            if ($user->position === 'pengawas') {
                $query->whereHas('report_project.deal_project.deal_project_users', function($q) use ($user) {
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
    public function create($report_project_id)
    {
        return view('contractor.constraint.create', compact('report_project_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConstraintCreateRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            foreach ($data['entries'] as $entry) {
                Constraints::create([
                    'report_project_id' => $data['report_project_id'],
                    'tanggal' => $entry['tanggal'],
                    'pekerjaan' => $entry['pekerjaan'],
                    'progress' => $entry['progress'],
                    'kendala' => $entry['kendala'],
                    ]);
            }
            DB::commit();
                return redirect()->route('constraints')->with('success', 'Project entries created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating project entries. Please try again.')->withInput();
        }
        return redirect()->route('constraints')->with('success', 'Material created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $constraint = Constraints::with('operational_project')->find($id);
        if (!$constraint) {
            return redirect()->route('constraints')
            ->with('error', 'Constraint dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('contractor.constraint.show', compact('prospect'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $constraint = Constraints::find($id);
        $operationalProject = DealProject::all();
        if (!$constraint) {
            return redirect()->route('constraints')
            ->with('error', 'Constraint dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('contractor.constraint.edit', compact('material', 'operationalProject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ConstraintUpdateRequest $request, string $id)
    {
        $constraint = Constraints::find($id);
        if (!$constraint) {
            return redirect()->route('constraints')
            ->with('error', 'Constraint dengan ID ' . $id . ' tidak ditemukan.');
        }
        $data = $request->validated();
        $constraint->update($data);
        return redirect()->route('constraints')->with('success', 'Constraint updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $constraint = Constraints::find($id);
        if (!$constraint) {
            return redirect()->route('constraints')
            ->with('error', 'Constraint dengan ID ' . $id . ' tidak ditemukan.');
        }
        $constraint->delete();
        return redirect()->route('constraints')->with('success', 'Constraint deleted successfully.');
    }
}
