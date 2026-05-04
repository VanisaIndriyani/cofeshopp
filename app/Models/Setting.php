<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Setting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
    ];

    private static ?array $cache = null;

    public static function get(string $key, mixed $default = null): mixed
    {
        return Arr::get(self::allAsArray(), $key, $default);
    }

    public static function bool(string $key, bool $default = false): bool
    {
        $value = self::get($key, $default ? '1' : '0');
        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? $default;
    }

    public static function number(string $key, float|int $default = 0): float
    {
        $value = self::get($key, $default);
        return is_numeric($value) ? (float) $value : (float) $default;
    }

    public static function set(string $key, mixed $value, string $group = 'general'): void
    {
        self::query()->updateOrCreate(
            ['key' => $key],
            ['group' => $group, 'value' => is_scalar($value) || $value === null ? (string) $value : json_encode($value)]
        );

        self::$cache = null;
    }

    public static function allAsArray(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        try {
            self::$cache = self::query()->pluck('value', 'key')->toArray();
        } catch (\Throwable $e) {
            self::$cache = [];
        }

        return self::$cache;
    }
}
