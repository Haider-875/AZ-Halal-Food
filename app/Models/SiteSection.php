<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSection extends Model
{
    protected $fillable = ['key', 'section_group', 'label', 'content', 'type'];

    public static function getValue(string $key, string $default = ''): string
    {
        $item = self::where('key', $key)->first();
        return $item ? ($item->content ?? $default) : $default;
    }

    public static function getJson(string $key, array $default = []): array
    {
        $item = self::where('key', $key)->first();
        if (!$item || empty($item->content)) return $default;
        $decoded = json_decode($item->content, true);
        return is_array($decoded) ? $decoded : $default;
    }
}
