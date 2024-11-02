<?php

namespace App\Charts;

use App\Models\Prospect;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;

class ProspectChart
{
    protected $chart;
    protected $year;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
        $this->year = date('Y'); // Default to current year
    }

    public function setYear($year)
    {
        $this->year = $year;
        return $this;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\LineChart
    {
        $months = [];
        $prospectData = [];
        $surveyData = [];
        $dealData = [];

        // Get data for all 12 months
        for ($month = 1; $month <= 12; $month++) {
            $months[] = Carbon::create()->month($month)->format('F');

            // Count prospects for each status in this month
            $prospectData[] = Prospect::whereYear('created_at', $this->year)
                ->whereMonth('created_at', $month)
                ->where('status', 'prospek')
                ->count();

            $surveyData[] = Prospect::whereYear('created_at', $this->year)
                ->whereMonth('created_at', $month)
                ->where('status', 'survey')
                ->count();

            $dealData[] = Prospect::whereYear('created_at', $this->year)
                ->whereMonth('created_at', $month)
                ->where('status', 'deal')
                ->count();
        }

        return $this->chart->lineChart()
            ->setTitle('Prospect Data ' . $this->year)
            ->setSubtitle('Monthly distribution of Prospects, Surveys, and Deals')
            ->addData('Prospects', $prospectData)
            ->addData('Surveys', $surveyData)
            ->addData('Deals', $dealData)
            ->setHeight(350)
            ->setXAxis($months);
    }
}
