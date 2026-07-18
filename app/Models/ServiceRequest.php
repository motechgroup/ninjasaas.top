<?php

namespace App\Models;

use App\Enums\ServiceRequestStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'product_id',
        'description',
        'budget',
        'status',
        'quote_price',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => ServiceRequestStatus::class,
            'budget' => 'decimal:2',
            'quote_price' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function mediaFiles()
    {
        return $this->morphMany(MediaFile::class, 'model');
    }
}
