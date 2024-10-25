<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory, HasUuids;
    protected $table = "surveys";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = [
        'date','prospect_id','survey_results'
    ];

    public function prospect()
    {
        return $this->belongsTo(Prospect::class, 'prospect_id', 'id');
    }
}
