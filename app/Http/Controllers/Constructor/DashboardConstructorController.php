<?php

namespace App\Http\Controllers\Constructor;

use App\Charts\StatusProspectChart;
use App\Http\Controllers\Controller;
use App\Models\Prospect;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardConstructorController extends Controller
{
    public function countProspekCurrentMonth()
    {
        return Prospect::where('status', 'prospek')
            ->whereYear('tanggal', Carbon::now()->year)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->count();
    }

    public function countSurveyCurrentMonth()
    {
        return Prospect::where('status', 'survey')
            ->whereYear('tanggal', Carbon::now()->year)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->count();
    }

    public function countDealCurrentMonth()
    {
        return Prospect::where('status', 'deal')
            ->whereYear('tanggal', Carbon::now()->year)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->count();
    }

    public function index()
    {
        return view('contractor.index', [
            'countProspek' => $this->countProspekCurrentMonth(),
            'countSurvey' => $this->countSurveyCurrentMonth(),
            'countDeal' => $this->countDealCurrentMonth(),
        ]);
    }

    public function showChart(Request $request)
    {
        // Get the specified year or default to the current year
        $year = $request->input('year', Carbon::now()->year);

        // Retrieve prospect data, grouped by status for the specified year
        $prospects = Prospect::whereYear('tanggal', $year)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Define the labels and initialize counts to zero for each status
        $statusLabels = ['prospek', 'survey', 'deal'];
        $statusCounts = array_fill_keys($statusLabels, 0);
        foreach ($prospects as $prospect) {
            $statusCounts[$prospect->status] = $prospect->count;
        }
        return view('contractor.index', [
            'statusCounts' => $statusCounts,
            'year' => $year,
        ]);
    }
}
