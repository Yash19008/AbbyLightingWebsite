<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blogs';

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'dek',
        'author',
        'published_at',
        'read_time',
        'featured_image',
        'featured_image_caption',
        'secondary_image',
        'secondary_image_caption',
        'content',
        'table_of_contents',
        'pull_quote',
        'quote_author',
        'status',
        'is_featured',
        'views_count',
        'sort_order',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'table_of_contents' => 'array',
        'published_at' => 'date',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }
}
