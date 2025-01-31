<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EskulEvent extends Model
{
    protected $table = 'eskul_events';

    protected $fillable = [
        'eskul_id',

        'created_by',
        'title',
        'description',
        'start_datetime',
        'end_datetime',
        'location',
        'quota',
        'requires_registration'
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'requires_registration' => 'boolean'
    ];

    public function eskul(): BelongsTo
    {
        return $this->belongsTo(Eskul::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(EskulEventParticipant::class, 'event_id');
    }

    public function isRegistrationOpen(): bool
    {
        $now = now();
        
        // Cek apakah event masih bisa didaftar berdasarkan:
        // 1. Waktu sekarang belum melewati end_datetime
        // 2. Kuota masih tersedia (jika ada kuota)
        
        if ($now->isAfter($this->end_datetime)) {
            return false;
        }

        if ($this->quota && $this->participants->count() >= $this->quota) {
            return false;
        }

        return true;
    }

    public function hasUserRegistered(User $user): bool
    {
        return $this->participants()->where('student_id', $user->id)->exists();
    }
} 