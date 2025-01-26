<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'nis',
        'nisn',
        'gender',
        'birth_place',
        'birth_date',
        'address',
        'phone',
        'religion',
        'class',
        'academic_year',
        'father_name',
        'father_occupation',
        'father_phone',
        'mother_name',
        'mother_occupation',
        'mother_phone',
        'guardian_name',
        'guardian_occupation',
        'guardian_phone',
        'guardian_relation',
        'medical_history',
        'special_needs',
        'notes'
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    /**
     * Get the user that owns the detail.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 