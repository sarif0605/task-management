<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDivisiPosition extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    protected $table = "user_divisi_positions";

    protected $fillable = [
        'position_id',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function position(){
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }
}
