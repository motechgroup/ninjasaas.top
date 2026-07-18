<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVersion extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'version', 'release_date', 'download_url', 'is_stable'];

    protected $casts = [
        'release_date' => 'date',
        'is_stable' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function changelogs()
    {
        return $this->hasMany(ProductChangelog::class);
    }
}
