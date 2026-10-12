<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' && $this->is_active;
    }

    /**
     * @return HasMany<LoadSheddingSchedule, $this>
     */
    public function schedulesCreated(): HasMany
    {
        return $this->hasMany(LoadSheddingSchedule::class, 'created_by');
    }

    /**
     * @return HasMany<Outage, $this>
     */
    public function outagesReported(): HasMany
    {
        return $this->hasMany(Outage::class, 'reported_by');
    }

    /**
     * @return HasMany<Announcement, $this>
     */
    public function announcementsCreated(): HasMany
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }
}
