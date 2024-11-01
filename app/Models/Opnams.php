<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opnams extends Model
{
    use HasFactory, HasUlids;

    protected $table = "opnams";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'deal_project_id',
        'pekerjaan',
        'date',
    ];

    public function kasbon_opnam(){
        return $this->hasMany(KasbonOpnams::class, 'opnam_id', 'id');
    }

    public function deal_project()
    {
        return $this->belongsTo(DealProject::class, 'deal_project_id', 'id');
    }
}
