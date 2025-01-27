<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EskulTest extends Model
{
    protected $fillable = [
        'eskul_id',
        'created_by',
        'title',
        'description',
        'start_time',
        'end_time',
        'duration_minutes',
        'passing_score',
        'is_active',
        'test_type'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function eskul()
    {
        return $this->belongsTo(Eskul::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions()
    {
        return $this->hasMany(TestQuestion::class, 'test_id');
    }

    public function attempts()
    {
        return $this->hasMany(TestAttempt::class, 'test_id');
    }
} 