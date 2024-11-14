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
    protected $fillable = ['nama_produk', 'tanggal', 'pemilik', 'lokasi','keterangan', 'status'
    ];

    public function survey()
    {
        return $this->hasMany(Survey::class, 'prospect_id', 'id');
    }

    public function deal_project()
    {
        return $this->hasMany(DealProject::class, 'prospect_id', 'id');
    }

    public function penawaran_project()
    {
        return $this->hasMany(PenawaranProject::class, 'prospect_id', 'id');
    }
}
