<?php

namespace App\Http\Controllers\Constructor;

use App\Charts\ProspectStatusChart;
use App\Http\Controllers\Controller;
use App\Models\Prospect;
use Carbon\Carbon;

class DashboardConstructorController extends Controller
{
    public function index()
    {
        $tahun = date('Y');
        $bulan = date('m');

        $dataBulan = [];
        $dataProspek = [];
        $dataSurvey = [];
        $dataPenawaran = [];
        $dataDeal = [];

        for ($i = 1; $i <= $bulan; $i++) {
            $dataBulan[] = Carbon::create()->month($i)->format('F');
            $dataProspek[] = Prospect::whereYear('created_at', $tahun)
                ->whereMonth('created_at', $i)
                ->where('status', 'prospek')
                ->count();
            $dataSurvey[] = Prospect::whereYear('created_at', $tahun)
                ->whereMonth('created_at', $i)
                ->where('status', 'survey')
                ->count();
            $dataPenawaran[] = Prospect::whereYear('created_at', $tahun)
                ->whereMonth('created_at', $i)
                ->where('status', 'penawaran')
                ->count();
            $dataDeal[] = Prospect::whereYear('created_at', $tahun)
                ->whereMonth('created_at', $i)
                ->where('status', 'deal')
                ->count();
        }

        $countProspek = Prospect::where('status', 'prospek')
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->count();

        $countSurvey = Prospect::where('status', 'survey')
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->count();

        $countPenawaran = Prospect::where('status', 'penawaran')
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->count();

        $countDeal = Prospect::where('status', 'deal')
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->count();

        $dataStatus = [
            'prospek' => Prospect::where('status', 'prospek')->count(),
            'survey' => Prospect::where('status', 'survey')->count(),
            'penawaran' => Prospect::where('status', 'penawaran')->count(),
            'deal' => Prospect::where('status', 'deal')->count(),
        ];

        return view('contractor.index', [
            'countProspek' => $countProspek,
            'countSurvey' => $countSurvey,
            'countPenawaran' => $countPenawaran,
            'countDeal' => $countDeal,
            'labels' => $dataBulan,
            'dataProspek' => $dataProspek,
            'dataSurvey' => $dataSurvey,
            'dataPenawaran' => $dataPenawaran,
            'dataDeal' => $dataDeal,
            'status' => $dataStatus,
        ]);
    }
}
