<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSection extends Model
{
    protected $fillable = ['key', 'section_group', 'label', 'content', 'type'];

    protected static ?array $memoryCache = null;

    protected static function booted(): void
    {
        static::saved(function () {
            static::clearCache();
        });
        static::deleted(function () {
            static::clearCache();
        });
    }

    public static function clearCache(): void
    {
        static::$memoryCache = null;
        try {
            Cache::forget('site_sections_map');
        } catch (\Throwable $e) {
            // Ignore cache errors if cache store is unreachable
        }
    }

    public static function getAllCached(): array
    {
        if (static::$memoryCache !== null) {
            return static::$memoryCache;
        }

        try {
            static::$memoryCache = Cache::remember('site_sections_map', 3600, function () {
                return self::pluck('content', 'key')->toArray();
            });
        } catch (\Throwable $e) {
            try {
                static::$memoryCache = self::pluck('content', 'key')->toArray();
            } catch (\Throwable $e2) {
                static::$memoryCache = [];
            }
        }

        return static::$memoryCache ?? [];
    }

    public static function getValue(string $key, string $default = ''): string
    {
        $all = self::getAllCached();

        if (array_key_exists($key, $all)) {
            return $all[$key] !== null ? (string) $all[$key] : $default;
        }

        return $default;
    }

    public static function getJson(string $key, array $default = []): array
    {
        $content = self::getValue($key, '');
        if (empty($content)) {
            return $default;
        }
        $decoded = json_decode($content, true);

        return is_array($decoded) ? $decoded : $default;
    }
}
