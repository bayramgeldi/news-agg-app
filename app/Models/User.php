<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    public function canAccessFilament(): bool
    {
        return str_ends_with($this->email, '@newsagg.app') && $this->hasVerifiedEmail();
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * @return HasMany
     */
    public function sourceSettings(): HasMany
    {
        return $this->hasMany(UserSourceSetting::class);
    }

    /**
     * @return HasMany
     */
    public function categorySettings(): HasMany
    {
        return $this->hasMany(UserCategorySetting::class);
    }

    /**
     * @return HasMany
     */
    public function authorSettings(): HasMany
    {
        return $this->hasMany(UserAuthorSetting::class);
    }
}
