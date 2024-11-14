<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenawaranProject extends Model
{
    use HasFactory, HasUuids;
    protected $primaryKey = "id";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamps = true;
    protected $table = "penawaran_projects";

    protected $fillable = [
        'pembuat_penawaran',
        'prospect_id',
        'file_pdf',
        'file_excel',
        'pdf_public_id',
        'excel_public_id'
    ];

    public function prospect()
    {
        return $this->belongsTo(Prospect::class);
    }
}
