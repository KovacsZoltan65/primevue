<?php

namespace App\Services;

use Illuminate\Cache\RedisStore;
use Illuminate\Cache\TaggableStore;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use function config;

class CacheService
{
    public function put(string $tag, string $key, $value, int $ttl = 3600): void
    {
        $cacheKey = "{$tag}_{$key}";

        if (Cache::supportsTags()) {
            Cache::tags([$tag])->put($cacheKey, $value, $ttl);
        } else {
            Cache::put($cacheKey, $value, $ttl);
            $this->storeKey($tag, $cacheKey);
        }
    }

    public function remember($tag, $key, $callback, $ttl = 3600)
    {
        $cacheKey = "{$tag}_{$key}";

        if (Cache::supportsTags()) {
            return Cache::tags([$tag])->remember($cacheKey, $ttl, $callback);
        }

        return Cache::remember($cacheKey, $ttl, $callback);
    }

    public function forgetAll($tag): void
    {
        if (Cache::supportsTags()) {
            Cache::tags([$tag])->flush();
        } else {
            $keys = Cache::get("{$tag}_keys", []);
            foreach ($keys as $key) {
                Cache::forget($key);
            }
            Cache::forget("{$tag}_keys");
        }
    }

    public function forgetByTag(string $tag): void
    {
        if (Cache::getStore() instanceof TaggableStore) {
            // Ha a cache támogatja a tag-eket
            Cache::tags($tag)->flush();
        } else {
            // Alternatív megoldás: Egyenként töröljük a kulcsokat a Redis prefix alapján
            $this->forgetAllMatching("{$tag}_*");
        }
    }
    
    public function forgetAllMatching(string $pattern): void
    {
        $store = Cache::getStore();

        if ($store instanceof RedisStore) {
            // Redis cache: a kulcsok direkt lekérdezése
            $keys = $store->connection()->keys(config('cache.prefix') . ":{$pattern}");
            foreach ($keys as $key) {
                Cache::forget(str_replace(config('cache.prefix') . ':', '', $key));
            }
        } elseif (method_exists($store, 'getKeys')) {
            // Más támogatott cache-driverek: közvetlen kulcs lista lekérdezése
            $keys = $store->getKeys($pattern);
            foreach ($keys as $key) {
                Cache::forget($key);
            }
        } else {
            // Ha a cache nem támogat mintázat-alapú törlést, figyelmeztetés
            Log::warning("Cache driver does not support pattern-based deletion.");
        }
    }
    
    protected function storeKey($tag, $key): void
    {
        $keys = Cache::get("{$tag}_keys", []);
        if (!in_array($key, $keys)) {
            $keys[] = $key;
            Cache::put("{$tag}_keys", $keys, 3600);
        }
    }
}
