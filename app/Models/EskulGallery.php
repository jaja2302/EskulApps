<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EskulGallery extends Model
{
    protected $table = 'eskul_galleries';

    protected $fillable = [
        'eskul_id',
        'uploaded_by',
        'title',
        'description',
        'media_type',

        'file_path',
        'event_date'
    ];

    protected $casts = [
        'event_date' => 'date'
    ];

    public function eskul(): BelongsTo
    {
        return $this->belongsTo(Eskul::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
} 