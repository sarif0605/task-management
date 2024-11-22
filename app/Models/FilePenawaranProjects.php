<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilePenawaranProjects extends Model{
    use HasFactory, HasUlids;

    protected $table = "file_penawaran_projects";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'penawaran_project_id',
        'file',
    ];

    public function penawaran_project()
    {
        return $this->belongsTo(PenawaranProject::class, 'penawaran_project_id', 'id');
    }
}
