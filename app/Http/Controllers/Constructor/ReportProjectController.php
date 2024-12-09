<?php

namespace App\Http\Controllers\Constructor;

use App\Exports\ReportProjectsExport;
use App\Http\Controllers\Controller;
use App\Models\DealProject;
use App\Models\DealProjectUsers;
use App\Models\ReportProject;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ReportProjectController extends Controller
{
    private function getProgressDetails($dealProjectId)
    {
        $reportProjects = ReportProject::where('deal_project_id', $dealProjectId)
            ->where('status', '!=', 'plan')
            ->get();
        $totalWeight = $reportProjects->sum('bobot');
        $weightedProgressSum = $reportProjects->sum(function ($project) {
            return $project->bobot * ($project->progress / 100);
        });

        $overallProgress = $totalWeight > 0
            ? round(($weightedProgressSum / $totalWeight) * 100, 2)
            : 0;
        $completedPercentage = $reportProjects->count() > 0
                        ? round(($reportProjects->where('status', 'selesai')->count() / $reportProjects->count()) * 100, 2)
                        : 0;
        $remainingPercentage = $reportProjects->count() > 0
                        ? round((($reportProjects->count() - $reportProjects->where('status', 'selesai')->count()) / $reportProjects->count()) * 100, 2)
                        : 0;
        Log::info('Progress Details', [
            'completedPercentage' => $completedPercentage, // Ensure this is set
            'remainingPercentage' => $remainingPercentage, // Ensure this is set
        ]);
        return [
            'totalWeight' => round($totalWeight, 2),
            'overallProgress' => $overallProgress,
            'totalTasks' => $reportProjects->count(),
            'completedTasks' => $reportProjects->where('status', 'selesai')->count(),
            'completedPercentage' => $completedPercentage, // Ensure this is set
            'remainingPercentage' => $remainingPercentage, // Ensure this is set
        ];
    }

    public function index(Request $request)
    {
        $deal_project_id = $request->input('deal_project_id');
        $user = Auth::user();
        $isSupervisor = $user->hasPosition('Pengawas'); // Cek posisi user
        $dealProjects = collect();
        $report = collect();
        $progressDetails = null;

        // Ambil dealProjects berdasarkan posisi user
        if ($isSupervisor) {
            $dealProjects = DealProject::whereHas('deal_project_users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();
            Log::info('Pengawas', ['dealProjects' => $dealProjects]);
        } else {
            $dealProjects = DealProject::all(); // Tampilkan semua untuk selain Pengawas
        }

        // Ambil laporan dan detail progres jika ada deal_project_id
        if ($deal_project_id) {
            $report = ReportProject::with(['deal_project.prospect', 'updatedBy'])
                ->where('deal_project_id', $deal_project_id)
                ->orderBy('excel_row_number')
                ->get();

            $progressDetails = $this->getProgressDetails($deal_project_id);
        }

        // Jika permintaan AJAX, kembalikan data sebagai JSON
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'data' => $report,
                'progress' => [
                    'totalProgress' => $progressDetails['overallProgress'] ?? 0,
                    'totalBobot' => $progressDetails['totalWeight'] ?? 0,
                    'totalTasks' => $progressDetails['totalTasks'] ?? 0,
                    'completedTasks' => $progressDetails['completedTasks'] ?? 0,
                    'completedPercentage' => $progressDetails['completedPercentage'] ?? 0,
                    'remainingPercentage' => $progressDetails['remainingPercentage'] ?? 0,
                ],
            ]);
        }

        // Kembalikan tampilan dengan data yang sesuai
        return view('contractor.report_project.index', [
            'deals' => $dealProjects,
            'progress' => $progressDetails,
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
    public function edit($id)
    {
        $report = ReportProject::find($id);
        if (!$report) {
            return response()->json(['error' => 'Report not found'], 404);
        }
        return response()->json($report);
    }


    public function update(Request $request, $id)
    {
        Log::info('id', ['id' => $id]);
        $validatedData = $request->validate([
            'status' => 'required|in:plan,mulai,selesai,belum',
        ]);
        $reportProject = ReportProject::findOrFail($id);
        $reportProject->status = $validatedData['status'];
        $reportProject->updated_by = Auth::id();
        if ($validatedData['status'] === 'mulai') {
            $reportProject->start_date = $reportProject->start_date ?? now();
        }
        if ($validatedData['status'] === 'selesai') {
            $reportProject->end_date = now();
            $reportProject->progress = $reportProject->bobot;
            $reportProject->harian = 0;
            $reportProject->durasi = $reportProject->end_date->diffInDays($reportProject->start_date);
        }
        $reportProject->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Project update recorded successfully',
        ]);
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
