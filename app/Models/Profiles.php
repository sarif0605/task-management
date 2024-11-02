<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profiles extends Model
{
    use HasFactory, HasUlids;

    protected $table = "profiles";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'name',
        'nik',
        'birth_date',
        'address',
        'phone',
        'foto',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
