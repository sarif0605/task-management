<?php

namespace App\Http\Controllers\Constructor;

use App\Http\Requests\Opnam\OpnamCreateRequest;
use App\Http\Requests\Opnam\OpnamUpdateRequest;
use App\Models\Opnams;
use App\Http\Controllers\Controller;
use App\Models\DealProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OpnamController extends Controller
{

    public function __construct()
    {
        $this->middleware(['isVerificationAccount', 'isStatusAccount'])->only('store', 'create', 'edit', 'update', 'destroy');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();
            $query = Opnams::with([
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
        return view('contractor.opnam.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($report_project_id)
    {
        return view('contractor.opnam.create', compact('report_project_id'));
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(OpnamCreateRequest $request)
    {
        $data = $request->validated();
    try {
        DB::beginTransaction();
        foreach ($data['entries'] as $entry) {
            Opnams::create([
                'report_project_id' => $data['report_project_id'],
                // 'lokasi' => $entry['lokasi'],
                'pekerjaan' => $entry['pekerjaan'],
                'date' => $entry['date']
            ]);
        }
        DB::commit();
            return redirect()->route('opnams')->with('success', 'Project entries created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating project entries. Please try again.')
                        ->withInput();
        }
        return redirect()->route('opnams')->with('success', 'Material created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $opnam = Opnams::with('operational_project')->find($id);
        if (!$opnam) {
            return redirect()->route('opnams')
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
            return redirect()->route('opnams')
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
            return redirect()->route('opnams.edit')
            ->with('error', 'Opnam dengan ID ' . $id . ' tidak ditemukan.');
        }
        $data = $request->validated();
        $opnam->update($data);
        return redirect()->route('opnams')->with('success', 'Opnam updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $opnam = Opnams::find($id);
        if (!$opnam) {
            return redirect()->route('opnams')
            ->with('error', 'Opnam dengan ID ' . $id . ' tidak ditemukan.');
        }
        $opnam->delete();
        return redirect()->route('opnams')->with('success', 'Material deleted successfully.');
    }
}
