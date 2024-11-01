<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasbonOpnams extends Model
{
    use HasFactory, HasUlids;

    protected $table = "kasbon_opnams";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'opnam_id',
        'nominal',
        'keterangan',
    ];

    public function opnam(){
        return $this->belongsTo(Opnams::class, 'opnam_id', 'id');
    }
}
