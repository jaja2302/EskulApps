<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EskulMember extends Model
{
    protected $table = 'eskul_members';

    protected $fillable = [
        'student_id',
        'eskul_id',
        'added_by',
        'is_active',
        'join_date',
        'end_date',
        'notes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'join_date' => 'date',
        'end_date' => 'date'
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function eskul(): BelongsTo
    {
        return $this->belongsTo(Eskul::class);
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    // Helper method to get student detail
    public function studentDetail()
    {
        return $this->student()->with('detail');
    }
} 