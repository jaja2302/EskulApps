<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Eskul extends Model
{
    //
    protected $fillable = [
        'name',
        'description',
        'image',
        'banner_image',
        'pelatih_id',
        'pembina_id',
        'quota',
        'is_active',
        'meeting_location',
        'requirements',
        'category',
        'kouta_unlimited'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function pelatih(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pelatih_id');
    }

    public function pembina(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pembina_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(EskulMember::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(EskulSchedule::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(EskulMaterial::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(EskulEvent::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(EskulGallery::class);
    }
}
