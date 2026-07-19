<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductSalesChannel extends Pivot
{
    protected $table = 'product_sales_channels';

    protected $fillable = [
        'product_id',
        'sales_channel_id',
        'purchase_url',
        'status',
        'priority',
        'price',
        'external_product_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'priority' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function channel()
    {
        return $this->belongsTo(SalesChannel::class, 'sales_channel_id');
    }
}
