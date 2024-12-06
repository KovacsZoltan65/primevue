<?php

namespace App\Providers;

class CacheServiceProvider
{   
    public function put($tag, $key, $value, $ttl = 3600) {
        $cacheKey = "{$tag}_{$key}";
        \Illuminate\Support\Facades\Cache::put($cacheKey, $value, $ttl);
    }
    
    public function forgetAll($tag) {
        $keys = Cache::get("{$tag}_keys", []);
        foreach ($keys as $key) {
            Cache::forget($key);
        }
        Cache::forget("{$tag}_keys");
    }
    
    public function storeKey($tag, $key) {
        $keys = Cache::get("{$tag}_keys", []);
        if (!in_array($key, $keys)) {
            $keys[] = $key;
            Cache::put("{$tag}_keys", $keys, 3600);
        }
    }
}
