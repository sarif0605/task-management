<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ReportProject extends Model
{
    use HasFactory, HasUuids;
    protected $table = "report_projects";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;

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
        'excel_row_number',
        'updated_by'
    ];

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
}
