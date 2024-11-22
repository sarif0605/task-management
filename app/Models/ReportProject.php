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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scope to get total progress
    public function scopeCalculateTotalProgress($query)
    {
        return $query->sum('progress');
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
}
