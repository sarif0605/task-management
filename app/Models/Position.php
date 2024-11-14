<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    protected $table = "positions";

    protected $fillable = [
        'name'
    ];

    public function usersDivisions()
    {
        return $this->hasMany(UserDivisiPosition::class);
    }
}
