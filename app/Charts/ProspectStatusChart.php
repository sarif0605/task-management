<?php

namespace App\Charts;

use App\Models\Prospect;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;

class ProspectStatusChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\LineChart
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

        return $this->chart->lineChart()
            ->setTitle('Prospect Status')
            ->setSubtitle('Monthly status breakdown for Prospects')
            ->addData('Prospek', $dataProspek)
            ->addData('Survey', $dataSurvey)
            ->addData('Penawaran', $dataPenawaran)
            ->addData('Deal', $dataDeal)
            ->setXAxis($dataBulan)
            ->setHeight(278);
    }
}
