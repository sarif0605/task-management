<?php

namespace App\Http\Controllers\Constructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportProject\ReportProjectUpdateRequest;
use App\Models\DealProject;
use App\Models\ReportProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportProjectController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();
            $query = ReportProject::with(['deal_project.prospect']);
            if ($user->position === 'pengawas') {
                $query->whereHas('deal_project.deal_project_users', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }
            $materials = $query->orderBy('created_at', 'DESC')->get();

            return response()->json(['data' => $materials]);
        }
        return view('contractor.report_project.index');
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create($deal_project_id) {
        return view('contractor.report_project.create', compact('deal_project_id'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validate([
                'deal_project_id' => 'required|exists:deal_projects,id',
                'entries' => 'required|array',
                'entries.*.status' => 'required|in:belum,mulai,selesai',
                'entries.*.start_date' => 'required|date',
                'entries.*.end_date' => 'required|date|after_or_equal:entries.*.start_date',
                'entries.*.bobot' => 'required|numeric|min:0',
                'entries.*.progress' => 'required|numeric|min:0|max:100',
                'entries.*.durasi' => 'required|numeric|min:0',
                'entries.*.harian' => 'required|numeric|min:0',
            ]);
            foreach ($validatedData['entries'] as $entry) {
                ReportProject::create([
                    'deal_project_id' => $validatedData['deal_project_id'],
                    'status' => $entry['status'],
                    'start_date' => $entry['start_date'],
                    'end_date' => $entry['end_date'],
                    'bobot' => $entry['bobot'],
                    'progress' => $entry['progress'],
                    'durasi' => $entry['durasi'],
                    'harian' => $entry['harian'],
                ]);
            }
            DB::commit();
            return redirect()->route('report_projects')
                           ->with('success', 'Project entries created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating project entries. Please try again.')
                        ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $report_project = ReportProject::with('deal_project.prospect')->find($id);
        if (!$report_project) {
            return redirect()->route('report_projects')
            ->with('error', 'Material dengan ID ' . $id . ' tidak ditemukan.');
        }
        return view('contractor.report_project.show', compact('report_project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $material = ReportProject::find($id);
        $operationalProject = DealProject::all();
        if (!$material) {
            return redirect()->route('report_projects.edit')
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
            return redirect()->route('report_projects.edit')
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
            return redirect()->route('report_projects')
            ->with('error', 'Material dengan ID ' . $id . ' tidak ditemukan.');
        }
        $material->delete();
        return redirect()->route('report_projects')->with('success', 'Material deleted successfully.');
    }
}
