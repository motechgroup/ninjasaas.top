<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
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
        'google_id',
        'two_factor_code',
        'two_factor_expires_at',
        'envato_username',
        'envato_avatar',
        'envato_token',
        'envato_refresh_token',
        'envato_token_expires_at',
        'bio',
        'profile_image',
        'twitter_handle',
        'github_handle',
        'status',
        'status_reason',
        'suspended_until',
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
            'two_factor_expires_at' => 'datetime',
            'suspended_until' => 'datetime',
        ];
    }

    public function isActive(): bool
    {
        if ($this->status === 'blocked') {
            return false;
        }

        if ($this->status === 'suspended') {
            if ($this->suspended_until && now()->greaterThan($this->suspended_until)) {
                $this->update(['status' => 'active', 'status_reason' => null, 'suspended_until' => null]);
                return true;
            }
            return false;
        }

        return true;
    }

    public function isSuspended(): bool
    {
        if ($this->status === 'suspended') {
            if ($this->suspended_until && now()->greaterThan($this->suspended_until)) {
                $this->update(['status' => 'active', 'status_reason' => null, 'suspended_until' => null]);
                return false;
            }
            return true;
        }

        return false;
    }

    public function isBlocked(): bool
    {
        return $this->status === 'blocked';
    }

    /**
     * Determine if the user has verified their email address.
     * Google users, Envato users, and Admin roles do not require manual email verification.
     */
    public function hasVerifiedEmail(): bool
    {
        if ($this->google_id || $this->envato_username) {
            return true;
        }

        if (method_exists($this, 'hasAnyRole') && $this->hasAnyRole(['Super Admin', 'Support Staff', 'Content Manager'])) {
            return true;
        }

        return ! is_null($this->email_verified_at);
    }

    public function purchases()
    {
        return $this->hasMany(EnvatoPurchase::class);
    }

    public function licenses()
    {
        return $this->hasMany(License::class);
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

    /**
     * Send 6-digit OTP code notification for email verification.
     */
    public function sendEmailVerificationNotification()
    {
        $code = (string) rand(100000, 999999);
        $this->update([
            'two_factor_code' => $code,
            'two_factor_expires_at' => now()->addMinutes(15),
        ]);

        $this->notify(new \App\Notifications\EmailVerificationOtpNotification($code));
    }
}
