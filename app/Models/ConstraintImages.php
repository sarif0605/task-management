<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConstraintImages extends Model
{
    use HasFactory, HasUlids;

    protected $table = "constraint_images";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'constraint_id',
        'image_link'
    ];

    public function constraint()
    {
        return $this->belongsTo(Constraints::class, 'constraint_id');
    }
}
