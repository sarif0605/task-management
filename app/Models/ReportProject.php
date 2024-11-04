<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportProject extends Model
{
    use HasFactory, HasUlids;
    protected $table = "report_projects";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = ['deal_project_id', 'status', 'start_date', 'end_date','bobot', 'progress', 'durasi', 'harian'
    ];

    public function deal_project(){
        return $this->belongsTo(DealProject::class, 'deal_project_id', 'id');
    }
}
