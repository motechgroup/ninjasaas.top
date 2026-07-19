<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
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
        'envato_username',
        'envato_avatar',
        'envato_token',
        'envato_refresh_token',
        'envato_token_expires_at',
        'bio',
        'profile_image',
        'twitter_handle',
        'github_handle',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'envato_token',
        'envato_refresh_token',
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
            'envato_token_expires_at' => 'datetime',
        ];
    }

    public function purchases()
    {
        return $this->hasMany(EnvatoPurchase::class);
    }

    public function tickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class);
    }
}
