<?php

namespace App\Http\Controllers\Constructor;

use App\Exports\ReportProjectsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReportProject\ReportProjectUpdateRequest;
use App\Models\DealProject;
use ArielMejiaDev\LarapexCharts\LarapexChart;
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
            $user = Auth::user();
            $query = ReportProject::with(['deal_project.prospect']); // Memuat relasi yang dibutuhkan
            if ($request->has('deal_project_id') && $request->deal_project_id) {
                $query->where('deal_project_id', $request->deal_project_id); // Filter berdasarkan deal_project_id
            }
            if ($user->position === 'pengawas') {
                $query->whereHas('deal_project.deal_project_users', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }
            $materials = $query->orderBy('created_at', 'DESC')->get();
            return response()->json(['data' => $materials]);
        }
        $deals = DealProject::all();
        return view('contractor.report_project.index', compact('deals'));
    }

    public function chart($dealProjectId)
    {
        // Get all report projects for this deal project
        $reportProjects = ReportProject::where('deal_project_id', $dealProjectId)->get();

        // Calculate total progress
        $totalProgress = $reportProjects->sum('progress');
        $totalWeight = $reportProjects->sum('bobot');

        // Create donut chart
        $chart = (new LarapexChart)->donutChart()
            ->setTitle('Project Progress')
            ->setSubtitle('Overall Project Status')
            ->addData([
                $totalProgress,
                $totalWeight - $totalProgress // Remaining progress
            ])
            ->setLabels(['Completed', 'Remaining']);

        return view('contractor.report_project.index', [
            'reportProjects' => $reportProjects,
            'chart' => $chart,
            'totalProgress' => $totalProgress,
            'totalWeight' => $totalWeight
        ]);
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
     * Update the specified resource in storage.
     */
    // public function update(ReportProjectUpdateRequest $request, string $id)
    // {
    //     $material = ReportProject::find($id);
    //     if (!$material) {
    //         return redirect()->route('report_projects.edit')
    //         ->with('error', 'Material dengan ID ' . $id . ' tidak ditemukan.');
    //     }
    //     $data = $request->validated();
    //     $material->update($data);
    //     return redirect()->route('contractor.material.index')->with('success', 'Material updated successfully.');
    // }

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
