<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentationArticle extends Model
{
    use HasFactory;

    protected $fillable = ['documentation_category_id', 'title', 'slug', 'content', 'sort_order', 'is_published'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(DocumentationCategory::class, 'documentation_category_id');
    }
}
