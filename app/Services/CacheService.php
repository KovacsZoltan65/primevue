<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

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

    protected function storeKey($tag, $key): void
    {
        $keys = Cache::get("{$tag}_keys", []);
        if (!in_array($key, $keys)) {
            $keys[] = $key;
            Cache::put("{$tag}_keys", $keys, 3600);
        }
    }
}
