<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ReportProject extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'deal_project_id',
        'pekerjaan',
        'status',
        'start_date',
        'end_date',
        'bobot',
        'progress',
        'durasi',
        'harian',
        'updated_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'bobot' => 'float',
        'progress' => 'float',
        'durasi' => 'float',
        'harian' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });

        static::saving(function ($model) {
            // Automatically calculate durasi when dates are set
            if ($model->start_date && $model->end_date) {
                $model->durasi = $model->start_date->diffInDays($model->end_date) + 1;
            }

            // Calculate harian if bobot and durasi are available
            if ($model->bobot && $model->durasi) {
                $model->harian = $model->bobot / $model->durasi;
            }
        });
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deal_project(){
        return $this->belongsTo(DealProject::class, 'deal_project_id', 'id');
    }

    public function constraints(){
        return $this->hasMany(Constraints::class, 'report_project_id', 'id');
    }

    public function opnams(){
        return $this->hasMany(Opnams::class, 'report_project_id', 'id');
    }

    public function materials(){
        return $this->hasMany(Materials::class, 'report_project_id', 'id');
    }

    // Helper methods for calculations
    public static function calculateProjectProgress($dealProjectId)
    {
        $reports = self::where('deal_project_id', $dealProjectId)->get();

        // Filter out reports where 'bobot' or 'progress' is null
        $validReports = $reports->filter(function ($report) {
            return !is_null($report->bobot) && !is_null($report->progress);
        });

        // Calculate total bobot and weighted progress
        $totalBobot = $validReports->sum('bobot');

        // Calculate weighted progress
        $weightedProgress = 0;
        foreach ($validReports as $report) {
            $weightedProgress += ($report->progress * $report->bobot / 100);
        }

        return [
            'totalBobot' => round($totalBobot, 2),
            'totalProgress' => round($weightedProgress, 2),
            'remainingProgress' => round($totalBobot > 0 ? ($totalBobot - $weightedProgress) : 0, 2)
        ];
    }
}
