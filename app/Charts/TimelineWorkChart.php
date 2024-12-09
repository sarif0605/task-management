<?php

namespace App\Charts;

use App\Models\ReportProject;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class TimelineWorkChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($dealProjectId): \ArielMejiaDev\LarapexCharts\DonutChart
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
        $incompleteProgress = 100 - $overallProgress;
        $data = [$overallProgress, $incompleteProgress];
        return $this->chart->donutChart()
            ->setTitle('Project Progress')
            ->setSubtitle('Weighted Progress Percentage')
            ->addData($data)
            ->setLabels(['Completed', 'Incomplete'])
            ->setColors(['#28a745', '#dc3545'])
            ->setHeight(200);
    }

    public function getProgressDetails($dealProjectId)
    {
        $reportProjects = ReportProject::where('deal_project_id', $dealProjectId)
            ->where('status', '!=', 'plan') // Exclude plan status
            ->get();

        $totalWeight = $reportProjects->sum('bobot');
        $weightedProgressSum = $reportProjects->sum(function ($project) {
            return $project->bobot * ($project->progress / 100);
        });

        $overallProgress = $totalWeight > 0
            ? round(($weightedProgressSum / $totalWeight) * 100, 2)
            : 0;

        return [
            'totalWeight' => round($totalWeight, 2),
            'overallProgress' => $overallProgress,
            'totalTasks' => $reportProjects->count(),
            'completedTasks' => $reportProjects->where('progress', 100)->count(),
        ];
    }
}

