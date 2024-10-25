<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealProject extends Model
{
    use HasFactory, HasUuids;
    protected $table = "dealprojects";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = [
        'date','prospect_id','price_quotation', 'nominal'
    ];

    public function prospect()
    {
        return $this->belongsTo(Prospect::class, 'prospect_id', 'id');
    }
}
