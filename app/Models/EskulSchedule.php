<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EskulSchedule extends Model
{
    protected $fillable = [
        'eskul_id',
        'day',
        'start_time',
        'end_time',
        'location',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    public function eskul(): BelongsTo
    {
        return $this->belongsTo(Eskul::class);
    }
} 