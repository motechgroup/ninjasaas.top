<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'type'];

    protected static array $cache = [];

    public static function get(string $key, $default = null)
    {
        if (array_key_exists($key, static::$cache)) {
            return static::$cache[$key] ?? $default;
        }

        $val = \Illuminate\Support\Facades\Cache::remember("setting.{$key}", 3600, function() use ($key) {
            $setting = self::where('key', $key)->first();
            if (!$setting) {
                return null;
            }

            return match ($setting->type) {
                'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
                'integer' => (int) $setting->value,
                'json' => json_decode($setting->value, true),
                default => $setting->value,
            };
        });

        static::$cache[$key] = $val;

        return $val ?? $default;
    }

    public static function set(string $key, $value, string $type = 'string'): void
    {
        $valueStr = is_array($value) || is_object($value) ? json_encode($value) : (string) $value;

        self::updateOrCreate(
            ['key' => $key],
            ['value' => $valueStr, 'type' => $type]
        );

        unset(static::$cache[$key]);
        \Illuminate\Support\Facades\Cache::forget("setting.{$key}");
    }
}
