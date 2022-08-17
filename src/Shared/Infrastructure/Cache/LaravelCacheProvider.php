<?php

namespace Qalis\Shared\Infrastructure\Cache;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Qalis\Shared\Domain\Cache\CacheProvider;

class LaravelCacheProvider implements CacheProvider  {

    public function get(string $key, $default = null)
    {
        return Cache::get($key, $default);
    }

    public function put(string $key, $value, $ttl = null): bool
    {
        return Cache::put($key, $value, $ttl);
    }

    public function remember(string $key, int $ttl, $callback)
    {
      return Cache::remember($key, $ttl, function () use($callback) {
         return $callback();
      });
   }

}
