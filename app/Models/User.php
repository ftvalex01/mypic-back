<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Hydrat\Laravel2FA\TwoFactorAuthenticatable;
use Hydrat\Laravel2FA\Contracts\TwoFactorAuthenticatableContract;


class User extends Authenticatable implements MustVerifyEmail, TwoFactorAuthenticatableContract
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'birth_date',
        'register_date',
        'bio',
        'email_verified_at',
        'available_pines',
        'profile_picture',
        'accumulated_points',
        'is_private',
        'is_2fa_enabled', // Asegúrate de que este campo esté incluido
        'google_id',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_options'

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'birth_date' => 'date',
        'register_date' => 'timestamp',
        'email_verified_at' => 'timestamp',
    ];



    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(Reaction::class);
    }

    // public function userFollowers(): HasMany
    // {
    //     return $this->hasMany(UserFollower::class);
    // }

    // public function userFollowings(): HasMany
    // {
    //     return $this->hasMany(UserFollowing::class);
    // }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }
    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }
    public function followers()
    {
        // Esta relación asume que hay una tabla 'user_followers' con columnas 'user_id' y 'follower_id'
        // 'follower_id' es el ID del usuario que sigue, 'user_id' es el ID del usuario que es seguido
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    public function following()
    {
        // La inversa de la relación anterior
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }
}
