<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Constraints extends Model
{
    use HasFactory, HasUuids;

    protected $table = "operational_projects";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'operational_project_id',
        'tanggal',
        'pekerjaan',
        'progress',
        'kendala'
    ];
}
