<?php

namespace App\Http\Controllers\Constructor;

use App\Charts\ProspectChart;
use App\Http\Controllers\Controller;
use App\Models\Prospect;
use Carbon\Carbon;

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

    public function dashboard(ProspectChart $chart)
    {
        $selectedYear = request('year', date('Y'));
        if (!is_numeric($selectedYear) || $selectedYear < 1900 || $selectedYear > 2100) {
            $selectedYear = date('Y');
        }
        $currentMonth = Carbon::now()->month;
        $data['countProspek'] = Prospect::whereYear('created_at', $selectedYear)
            ->whereMonth('created_at', $currentMonth)
            ->where('status', 'prospek')
            ->count();
        $data['countSurvey'] = Prospect::whereYear('created_at', $selectedYear)
            ->whereMonth('created_at', $currentMonth)
            ->where('status', 'survey')
            ->count();
        $data['countDeal'] = Prospect::whereYear('created_at', $selectedYear)
            ->whereMonth('created_at', $currentMonth)
            ->where('status', 'deal')
            ->count();
        $data['selectedYear'] = $selectedYear;
        $data['chart'] = $chart->setYear($selectedYear)->build();
        return view('contractor.index', $data);
    }
}
