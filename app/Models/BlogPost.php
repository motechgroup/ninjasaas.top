<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'subtitle',
        'slug',
        'summary',
        'content',
        'reading_time',
        'featured_image',
        'is_featured',
        'seo_title',
        'seo_description',
        'faq',
        'attachments',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'faq' => 'array',
        'attachments' => 'array',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categories()
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_post_category', 'blog_post_id', 'blog_category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_post_tag', 'blog_post_id', 'blog_tag_id');
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'blog_post_id');
    }

    public function approvedComments()
    {
        return $this->hasMany(BlogComment::class, 'blog_post_id')->where('is_approved', true)->latest();
    }

    /**
     * Local Scope to query only published posts that are scheduled for now or in the past.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Dynamic Reading Time Accessor (fall back to word count calculation)
     */
    public function getReadingTimeAttribute($value)
    {
        if ($value) {
            return $value;
        }
        $words = str_word_count(strip_tags($this->content));
        return max(1, (int) ceil($words / 200));
    }
}
