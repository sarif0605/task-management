<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyImages extends Model
{
    use HasFactory, HasUuids;

    protected $table = "survey_images";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = ['survey_id', 'image_url'];

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id', 'id');
    }
}
