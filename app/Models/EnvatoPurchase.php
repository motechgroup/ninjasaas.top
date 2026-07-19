<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EnvatoPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'envato_item_id',
        'purchase_code',
        'envato_username',
        'purchase_date',
        'support_expiry',
        'license_type',
        'is_active',
    ];

    protected $casts = [
        'purchase_date' => 'datetime',
        'support_expiry' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(EnvatoItem::class, 'envato_item_id');
    }

    public function verifications()
    {
        return $this->hasMany(LicenseVerification::class);
    }

    public function hasActiveSupport(): bool
    {
        return $this->support_expiry && $this->support_expiry->isFuture();
    }

    public function getProduct()
    {
        if (!$this->item) {
            return null;
        }
        return Product::where('envato_item_id', $this->item->item_id)->first();
    }
}
