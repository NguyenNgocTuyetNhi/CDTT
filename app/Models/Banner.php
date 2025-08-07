<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'beauty_banners';

    protected $fillable = [
        'title',
        'description',
        'image',
        'link',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Scope để lấy banner active
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order', 'asc');
    }
}
