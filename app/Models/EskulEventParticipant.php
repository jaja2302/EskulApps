<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EskulEventParticipant extends Model
{
    protected $table = 'eskul_event_participants';

    protected $fillable = [
        'event_id',
        'student_id',
        'status',
        'notes'
    ];


    public function event(): BelongsTo
    {
        return $this->belongsTo(EskulEvent::class, 'event_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
} 