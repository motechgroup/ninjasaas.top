<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentationCategory extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'name', 'slug', 'description', 'sort_order'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function articles()
    {
        return $this->hasMany(DocumentationArticle::class, 'documentation_category_id')->orderBy('sort_order');
    }
}
