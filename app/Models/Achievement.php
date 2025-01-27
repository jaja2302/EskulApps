<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Achievement extends Model
{
    protected $fillable = [
        'eskul_id',
        'student_id',
        'title',
        'description',
        'achievement_date',
        'level',
        'position',
        'certificate_file'
    ];

    protected $casts = [
        'achievement_date' => 'date'
    ];

    public function eskul(): BelongsTo
    {
        return $this->belongsTo(Eskul::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
} 