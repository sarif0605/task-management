<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prospect extends Model
{
    use HasFactory, HasUuids;
    protected $table = "prospects";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = [
        'name','tanggal','pemilik','lokasi','keterangan'
    ];

    public function survey()
    {
        return $this->hasMany(Survey::class, 'prospect_id', 'id');
    }

    public function deal_project()
    {
        return $this->hasMany(DealProject::class, 'prospect_id', 'id');
    }
}
