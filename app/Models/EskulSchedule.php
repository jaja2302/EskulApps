<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EskulSchedule extends Model
{
    protected $table = 'eskul_schedules';

    protected $fillable = [
        'eskul_id',
        'day',
        'start_time',
        'end_time',
        'location',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function eskul(): BelongsTo
    {
        return $this->belongsTo(Eskul::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'schedule_id');
    }
} 