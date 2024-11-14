<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealProjectUsers extends Model
{
    use HasFactory, HasUlids;

    protected $table = "deal_project_users";
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = ['deal_project_id', 'user_id'];

    public function deal_project(){
        return $this->belongsTo(DealProject::class, 'deal_project_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
