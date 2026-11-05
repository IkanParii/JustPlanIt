<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planner extends Model
{
    use HasFactory;

    // Kolom apa aja yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'deadline',
        'priority',
        'status',
    ];

    /**
     * Relasi ke User (1 Task ini cuma milik 1 User)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Attachment (1 Task bisa punya banyak lampiran dokumen)
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}