<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_category_id',
        'name',
        'slug',
        'short_description',
        'description',
        'image_url',
        'demo_url',
        'buy_url',
        'docs_url',
        'version',
        'is_active',
        'envato_item_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function features()
    {
        return $this->hasMany(ProductFeature::class)->orderBy('sort_order');
    }

    public function screenshots()
    {
        return $this->hasMany(ProductScreenshot::class)->orderBy('sort_order');
    }

    public function versions()
    {
        return $this->hasMany(ProductVersion::class)->orderByDesc('release_date');
    }

    public function docCategories()
    {
        return $this->hasMany(DocumentationCategory::class);
    }
}
