<?php

namespace App\Models;

class Category extends BaseModel
{
    protected $table = 'categories';
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'sort_order',
        'is_active',
        'meta_title',
        'meta_description'
    ];
    
    protected $casts = [
        'parent_id' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean'
    ];
    
    /**
     * Get parent category
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    
    /**
     * Get child categories
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    
    /**
     * Get all categories in tree structure
     */
    public static function tree()
    {
        return static::whereNull('parent_id')
                    ->orderBy('sort_order')
                    ->with('children');
    }
    
    /**
     * Get active categories
     */
    public static function active()
    {
        return static::where('is_active', true);
    }
    
    /**
     * Get root categories (no parent)
     */
    public static function roots()
    {
        return static::whereNull('parent_id');
    }
    
    /**
     * Check if category has children
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }
    
    /**
     * Check if category is root (no parent)
     */
    public function isRoot()
    {
        return $this->parent_id === null;
    }
    
    /**
     * Get full category path
     */
    public function getPath($separator = ' > ')
    {
        $path = [$this->name];
        $category = $this;
        
        while ($category->parent) {
            $category = $category->parent;
            array_unshift($path, $category->name);
        }
        
        return implode($separator, $path);
    }
    
    /**
     * Generate slug from name
     */
    public function generateSlug()
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->name)));
        $this->slug = $slug;
        return $this;
    }
    
    /**
     * Sort categories by order
     */
    public static function sorted()
    {
        return static::orderBy('sort_order');
    }
} 