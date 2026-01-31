<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'unit',
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
     * Check if user is a super admin (can see all units)
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' && $this->unit === null;
    }

    /**
     * Check if user is a unit admin (can only see their unit)
     */
    public function isUnitAdmin(): bool
    {
        return $this->role === 'admin' && $this->unit !== null;
    }

    /**
     * Check if user has access to a specific unit
     */
    public function hasAccessToUnit(string $unit): bool
    {
        // Super admin has access to all units
        if ($this->isAdmin()) {
            return true;
        }
        // Unit admin only has access to their own unit
        return $this->unit === $unit;
    }

    /**
     * Get the risks owned by the user
     */
    public function risks()
    {
        return $this->hasMany(Risk::class);
    }
}


