<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'attendances';
    protected $primaryKey = "id";
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'in_image',
        'in_time',
        'in_info',
        'out_image',
        'out_time',
        'out_info'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
