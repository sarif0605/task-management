<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materials extends Model
{
    use HasFactory, HasUlids;

    protected $table = "materials";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'deal_project_id',
        'tanggal',
        'pekerjaan',
        'material',
        'priority',
        'for_date',
        'keterangan',
    ];

    public function deal_project()
    {
        return $this->belongsTo(DealProject::class, 'deal_project_id', 'id');
    }

}
