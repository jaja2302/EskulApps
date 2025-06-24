<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's detail information
     */
    public function detail()
    {
        return $this->hasOne(UserDetail::class);
    }

    /**
     * Get the eskuls that the student is a member of
     */
    public function eskuls()
    {
        return $this->belongsToMany(Eskul::class, 'eskul_members', 'student_id', 'eskul_id')
            ->withPivot(['is_active', 'join_date', 'end_date', 'notes'])
            ->withTimestamps();
    }

    /**
     * Get the eskuls where user is a pelatih
     */
    public function eskulAsCoach()
    {
        return $this->hasMany(Eskul::class, 'pelatih_id');
    }

    /**
     * Get the eskuls where user is a pembina
     */
    public function eskulAsSupervisor()
    {
        return $this->hasMany(Eskul::class, 'pembina_id');
    }

    /**
     * Get the user's eskul memberships
     */
    public function eskulMemberships()
    {
        return $this->hasMany(EskulMember::class, 'student_id');
    }

    /**
     * Get active eskul memberships
     */
    public function activeEskulMemberships()
    {
        return $this->hasMany(EskulMember::class, 'student_id')->where('is_active', true);
    }

    /**
     * Get the user's attendance records
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }
}
