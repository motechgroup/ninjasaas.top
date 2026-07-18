<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LicenseVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'envato_purchase_id',
        'purchase_code',
        'product_id',
        'domain',
        'ip_address',
        'is_valid',
        'error_message',
    ];

    protected $casts = [
        'is_valid' => 'boolean',
    ];

    public function purchase()
    {
        return $this->belongsTo(EnvatoPurchase::class, 'envato_purchase_id');
    }
}
