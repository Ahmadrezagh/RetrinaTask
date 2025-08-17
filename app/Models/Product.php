<?php

namespace App\Models;

class Product extends BaseModel
{
    protected $table = 'products';
    
    protected $fillable = [
        // Add your fillable fields here
    ];
    
    protected $hidden = [
        // Add fields to hide from JSON output
    ];
    
    protected $casts = [
        // Add field type casting here
        // 'created_at' => 'datetime',
        // 'is_active' => 'boolean',
    ];
    
    // Add your model methods here
}
