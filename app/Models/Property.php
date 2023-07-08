<?php

namespace App\Models;

use App\Events\NewPropertyUpdateEvent;
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
        static::created(function ($model) {
            static::invalidateCache();
        });
    }

    public static function cacheKey($pageLength) {
        return 'properties-cache-'.request()->page ?? 1 . "-{$pageLength}";
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

    public static function broadcastNewPropertyUpdate($model) {
        event(new NewPropertyUpdateEvent($model));
    }
}
