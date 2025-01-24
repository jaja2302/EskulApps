<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Eskul extends Model
{
    //
    protected $fillable = [
        'name',
        'description',
        'image',
        'pelatih_id',
        'wakil_pelatih_id'
    ];

    public function pelatih(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pelatih_id');
    }

    public function wakil_pelatih(): BelongsTo
    {
        return $this->belongsTo(User::class, 'wakil_pelatih_id');
    }
    
}
