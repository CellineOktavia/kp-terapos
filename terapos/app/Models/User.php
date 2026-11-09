<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isCoOwner(): bool
    {
        return $this->role === 'co_owner';
    }

    public function getDisplayRoleAttribute(): string
    {
        return match ($this->role) {
            'owner' => 'Owner',
            'co_owner' => 'Co-Owner',
            default => ucfirst(str_replace('_', ' ', $this->role)),
        };
    }

    public function penjualans()
    {
        return $this->hasMany(Penjualan::class);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
