<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = ['title', 'content', 'type', 'is_active', 'sort_order'];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
