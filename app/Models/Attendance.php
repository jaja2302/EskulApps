<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'eskul_id',
        'schedule_id',
        'student_id',
        'date',
        'check_in_time',
        'status',
        'notes',
        'is_verified',
        'verified_by',
        'verified_at'
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_time' => 'datetime',
        'verified_at' => 'datetime',
        'is_verified' => 'boolean'
    ];

    public function eskul(): BelongsTo
    {
        return $this->belongsTo(Eskul::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(EskulSchedule::class, 'schedule_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
} 