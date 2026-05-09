<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'planner_id',
        'filename',
        'path',
        'size',
        'mime_type',
    ];

    /**
     * Relasi balik ke Planner (1 File ini nempel di 1 Task)
     */
    public function planner()
    {
        return $this->belongsTo(Planner::class);
    }
}