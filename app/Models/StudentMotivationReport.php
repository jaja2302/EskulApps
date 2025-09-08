<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentMotivationReport extends Model
{
    protected $fillable = [
        'student_id',
        'eskul_id',
        'created_by',
        'year',
        'semester',
        'month',
        'cluster',
        'attendance_score',
        'participation_score',
        'achievement_score',
        'motivation_reason',
        'recommendation',
        'status',
        'action_notes',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'attendance_score' => 'decimal:2',
        'participation_score' => 'decimal:2',
        'achievement_score' => 'decimal:2',
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function eskul(): BelongsTo
    {
        return $this->belongsTo(Eskul::class, 'eskul_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Scopes
    public function scopeNeedsMotivation($query)
    {
        return $query->where('cluster', 2);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPeriod($query, $year, $semester, $month = null)
    {
        $query->where('year', $year)
              ->where('semester', $semester);
              
        if ($month) {
            $query->where('month', $month);
        } else {
            $query->whereNull('month');
        }
        
        return $query;
    }

    // Accessors
    public function getClusterLabelAttribute()
    {
        return match($this->cluster) {
            0 => 'Sangat Aktif',
            1 => 'Cukup Aktif',
            2 => 'Perlu Motivasi',
            default => 'Unknown'
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Menunggu Review',
            'reviewed' => 'Sudah Direview',
            'action_taken' => 'Tindakan Diambil',
            default => 'Unknown'
        };
    }
}
