<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Subcategory extends Model
{
    protected $fillable = ['category_id', 'name', 'slug', 'description', 'is_active'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($sub) {
            if (empty($sub->slug)) {
                $sub->slug = Str::slug($sub->name);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
