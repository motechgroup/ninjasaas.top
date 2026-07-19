<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class License extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'license_provider_id',
        'license_key',
        'purchased_at',
        'expires_at',
        'support_expires_at',
        'is_active',
    ];

    protected $casts = [
        'purchased_at' => 'datetime',
        'expires_at' => 'datetime',
        'support_expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function provider()
    {
        return $this->belongsTo(LicenseProvider::class, 'license_provider_id');
    }

    public function activations()
    {
        return $this->hasMany(LicenseActivation::class);
    }

    public function hasActiveSupport(): bool
    {
        if (is_null($this->support_expires_at)) {
            return false;
        }

        return $this->support_expires_at->isFuture();
    }
}
