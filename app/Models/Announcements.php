<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcements extends Model
{
    protected $table = 'announcements';
    protected $fillable = [
        'eskul_id',
        'created_by',
        'title',
        'content',
        'is_important',
        'publish_at',
        'expire_at',
    ];

    public function eskul()
    {
        return $this->belongsTo(Eskul::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
}
