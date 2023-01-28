<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Property extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($user) {
            static::invalidateCache();
        });
    }

    public static function cacheKey() {
        return 'properties-cache-'.request()->page;
    }

    public static function cacheTTL() {
        return config('properties.cache-ttl');
    }

    public static function cacheTag() {
        return 'properties';
    }

    public static function invalidateCache() {
        Cache::tags([static::cacheTag()])->flush();
    }
}
