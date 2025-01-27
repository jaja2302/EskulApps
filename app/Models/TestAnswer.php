<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestAnswer extends Model
{
    protected $fillable = [
        'attempt_id',
        'question_id',
        'answer',
        'file_path',
        'score',
        'feedback',
        'scored_by',
        'scored_at'
    ];

    protected $casts = [
        'scored_at' => 'datetime'
    ];

    public function attempt()
    {
        return $this->belongsTo(TestAttempt::class);
    }

    public function question()
    {
        return $this->belongsTo(TestQuestion::class);
    }

    public function scorer()
    {
        return $this->belongsTo(User::class, 'scored_by');
    }
} 