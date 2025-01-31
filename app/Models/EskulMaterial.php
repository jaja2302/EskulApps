<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EskulMaterial extends Model
{
    protected $table = 'eskul_materials';

    protected $fillable = [
        'eskul_id',
        'uploaded_by',
        'title',
        'description',
        'file_path',
        'file_type'
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