<?php

namespace App\Http\Controllers\Constructor;

use App\Charts\ProspectStatusChart;
use App\Http\Controllers\Controller;
use App\Models\Prospect;
use Carbon\Carbon;

class DashboardConstructorController extends Controller
{
    public function index(ProspectStatusChart $chart)
    {
        // Count the status for this month
        $countProspek = Prospect::where('status', 'prospek')
            ->whereYear('tanggal', Carbon::now()->year)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->count();

        $countSurvey = Prospect::where('status', 'survey')
            ->whereYear('tanggal', Carbon::now()->year)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->count();

        $countPenawaran = Prospect::where('status', 'penawaran')
            ->whereYear('tanggal', Carbon::now()->year)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->count();

        $countDeal = Prospect::where('status', 'deal')
            ->whereYear('tanggal', Carbon::now()->year)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->count();
        $data['chart'] = $chart->build();
        $data['status'] = [
            'prospek' => Prospect::where('status', 'prospek')->count(),
            'survey' => Prospect::where('status', 'survey')->count(),
            'penawaran' => Prospect::where('status', 'penawaran')->count(),
            'deal' => Prospect::where('status', 'deal')->count(),
        ];

        // Pass the data to the view
        return view('contractor.index', [
            'countProspek' => $countProspek,
            'countSurvey' => $countSurvey,
            'countPenawaran' => $countPenawaran,
            'countDeal' => $countDeal,
            'chart' => $data['chart'],
            'status' => $data['status'],
        ]);
    }
}
