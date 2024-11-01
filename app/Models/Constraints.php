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
        'deal_project_id',
        'tanggal',
        'pekerjaan',
        'progress',
        'kendala'
    ];

    public function deal_project()
    {
        return $this->belongsTo(DealProject::class, 'deal_project_id', 'id');
    }

    public function constraint_image() {
        return $this->hasMany(ConstraintImages::class, 'constraint_id', 'id');
    }
}
