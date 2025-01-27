<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestQuestion extends Model
{
    protected $fillable = [
        'test_id',
        'question_type',
        'question',
        'options',
        'correct_answer',
        'score_weight',
        'answer_explanation'
    ];

    protected $casts = [
        'options' => 'array'
    ];

    public function test()
    {
        return $this->belongsTo(EskulTest::class, 'test_id');
    }

    public function answers()
    {
        return $this->hasMany(TestAnswer::class, 'question_id');
    }
} 