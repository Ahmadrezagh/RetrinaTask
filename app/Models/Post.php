<?php

namespace App\Models;

class Post extends BaseModel
{
    protected $table = 'posts';
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'status',
        'published_at',
        'view_count',
        'meta_title',
        'meta_description'
    ];
    
    protected $casts = [
        'published_at' => 'datetime',
        'view_count' => 'integer'
    ];
    
    /**
     * Get the user that owns the post
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Check if post is published
     */
    public function isPublished()
    {
        return $this->status === 'published' && $this->published_at <= date('Y-m-d H:i:s');
    }
    
    /**
     * Check if post is draft
     */
    public function isDraft()
    {
        return $this->status === 'draft';
    }
    
    /**
     * Get published posts
     */
    public static function published()
    {
        return static::where('status', 'published')
                    ->where('published_at', '<=', date('Y-m-d H:i:s'));
    }
    
    /**
     * Get draft posts
     */
    public static function drafts()
    {
        return static::where('status', 'draft');
    }
    
    /**
     * Get posts by user
     */
    public static function byUser($userId)
    {
        return static::where('user_id', $userId);
    }
    
    /**
     * Search posts by title or content
     */
    public static function search($query)
    {
        return static::where('title', 'LIKE', '%' . $query . '%')
                    ->orWhere('content', 'LIKE', '%' . $query . '%');
    }
    
    /**
     * Increment view count
     */
    public function incrementViews()
    {
        $this->view_count++;
        return $this->save();
    }
    
    /**
     * Generate slug from title
     */
    public function generateSlug()
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->title)));
        $this->slug = $slug;
        return $this;
    }
    
    /**
     * Set published status and timestamp
     */
    public function publish()
    {
        $this->status = 'published';
        $this->published_at = date('Y-m-d H:i:s');
        return $this->save();
    }
    
    /**
     * Set draft status
     */
    public function unpublish()
    {
        $this->status = 'draft';
        $this->published_at = null;
        return $this->save();
    }
} 