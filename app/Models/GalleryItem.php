<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    protected $fillable = ['src', 'alt', 'caption', 'tag', 'size', 'display_order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];
}
