<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'code',
        'name', 
        'description', 
        'price',
        'service_type',
        'schedule_notice',
        'processing_time',
        'image_path',
        'is_active',
        'active_schedule',
        'form_schema'
    ];

    protected $casts = [
        'form_schema' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
