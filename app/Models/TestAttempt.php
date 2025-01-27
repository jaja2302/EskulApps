<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestAttempt extends Model
{
    protected $fillable = [
        'test_id',
        'student_id',
        'start_time',
        'end_time',
        'score',
        'is_passed'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_passed' => 'boolean'
    ];

    public function test()
    {
        return $this->belongsTo(EskulTest::class, 'test_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function answers()
    {
        return $this->hasMany(TestAnswer::class, 'attempt_id');
    }
} 