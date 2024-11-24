<?php

namespace App\Http\Controllers\Constructor;

use App\Exports\ReportProjectsExport;
use App\Http\Controllers\Controller;
use App\Models\DealProject;
use App\Models\ReportProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportProjectController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $user = Auth::user();
                $query = ReportProject::with(['deal_project.prospect', 'updatedBy']);
                if ($request->has('deal_project_id') && $request->deal_project_id) {
                    $query->where('deal_project_id', $request->deal_project_id);
                    $progressData = ReportProject::calculateProjectProgress($request->deal_project_id);
                    $chartData = [
                        'chart' => [
                            'type' => 'donut'
                        ],
                        'title' => [
                            'text' => 'Project Progress'
                        ],
                        'series' => [
                            round($progressData['totalProgress'], 2),
                            round(100 - $progressData['totalProgress'], 2)
                        ],
                        'labels' => ['Completed', 'Remaining'],
                        'colors' => ['#3498db', '#e74c3c']
                    ];
                }

                if ($user->position === 'pengawas') {
                    $query->whereHas('deal_project.deal_project_users', function($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
                }

                $reports = $query->orderBy('created_at', 'DESC')->get();

                return response()->json([
                    'status' => 'success',
                    'data' => $reports,
                    'chart' => $chartData ?? null,
                    'progress' => isset($progressData) ? [
                        'totalProgress' => round($progressData['totalProgress'], 2),
                        'totalBobot' => round($progressData['totalBobot'], 2)
                    ] : null
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'An error occurred while processing your request',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        $deals = DealProject::all();
        return view('contractor.report_project.index', compact('deals'));
    }

    public function import(Request $request, $deal_project_id)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
        Excel::import(new ReportProjectsExport($deal_project_id), $request->file('file'));
        return redirect()->back()->with('success', 'Data berhasil diimport!');
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

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:plan,mulai,selesai,belum',
            'progress' => 'nullable|numeric|min:0|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date'
        ]);
        $reportProject = ReportProject::findOrFail($id);
        $validatedData['updated_by'] = Auth::id();

        $reportProject->update($validatedData);

        return redirect()->back()->with('success', 'Project update recorded successfully');
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
