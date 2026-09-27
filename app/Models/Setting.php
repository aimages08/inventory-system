<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group', 'type'];

    /**
     * Get a setting value (cached).
     */
    public static function get(string $key, $default = null)
    {
        $settings = Cache::rememberForever('app_settings', function () {
            return self::pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }

    /**
     * Set a setting value and clear cache.
     */
    public static function set(string $key, $value, string $group = 'general', string $type = 'string'): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group, 'type' => $type]
        );

        Cache::forget('app_settings');
    }

    /**
     * Bulk set.
     */
    public static function setMany(array $data): void
    {
        foreach ($data as $key => $value) {
            self::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        Cache::forget('app_settings');
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('app_settings'));
        static::deleted(fn () => Cache::forget('app_settings'));
    }
}