<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Constraints extends Model
{
    use HasFactory, HasUuids;

    protected $table = "constraints";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'report_project_id',
        'tanggal',
        'pekerjaan',
        'progress',
        'kendala'
    ];

    public function report_project()
    {
        return $this->belongsTo(ReportProject::class, 'report_project_id', 'id');
    }

    public function constraint_image() {
        return $this->hasMany(ConstraintImages::class, 'constraint_id', 'id');
    }
}
