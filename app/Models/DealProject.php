<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealProject extends Model
{
    use HasFactory, HasUuids;
    protected $table = "deal_projects";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = [ 'prospect_id', 'date', 'price_quotation', 'nominal', 'lokasi', 'keterangan'];

    public function prospect()
    {
        return $this->belongsTo(Prospect::class, 'prospect_id', 'id');
    }

    public function deal_project_users()
    {
        return $this->hasMany(DealProjectUsers::class, 'deal_project_id', 'id');
    }

    public function constraints()
    {
        return $this->hasMany(Constraints::class, 'deal_project_id', 'id');
    }

    public function opnams()
    {
        return $this->hasMany(Opnams::class, 'deal_project_id', 'id');
    }

    public function material(){
        return $this->hasMany(Materials::class, 'deal_project_id', 'id');
    }
}
