<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ShippingSetting extends Model
{
    protected $fillable = ['key', 'value', 'label', 'satuan'];

    /**
     * Ambil nilai setting by key, dengan cache 60 menit.
     */
    public static function get(string $key, float $default = 0): float
    {
        return Cache::remember("shipping_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? (float) $setting->value : $default;
        });
    }

    /**
     * Simpan nilai dan bust cache.
     */
    public static function set(string $key, float $value): void
    {
        static::where('key', $key)->update(['value' => $value]);
        Cache::forget("shipping_setting_{$key}");
    }
}
