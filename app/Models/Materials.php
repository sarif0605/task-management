<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materials extends Model
{
    use HasFactory, HasUlids;

    protected $table = "operational_projects";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'operational_project_id',
        'tanggal',
        'pekerjaan',
        'material',
        'priority',
        'for_date',
        'keterangan',
    ];
}
