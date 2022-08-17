<?php

namespace Qalis\Shared\Infrastructure\Controllers\ResourceLocking;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Qalis\Shared\Domain\Cache\CacheProvider;
use Qalis\Shared\Infrastructure\Cache\LaravelCacheProvider;

final class ResourceLocker {

    private CacheProvider $cacheProvider;

    public function __construct(CacheProvider $cacheProvider)
    {
        $this->cacheProvider = $cacheProvider;
    }

    private static function registerWriteKey(string $resourceCode, string $resourceId): string {
        return 'lockBefore_'.$resourceCode.'_'.$resourceId;
    }

    private static function registerReadKey(string $resourceCode, string $resourceId, $userId, string $fingerPrint): string {
        return 'resourceRead_'.$resourceCode.'_'.$resourceId.'_'.$userId.'_'.$fingerPrint;
    }

    public static function registerWrite(string $resourceCode, string $resourceId): void {
        $cacheProvider = new LaravelCacheProvider();
        $cacheProvider->put(self::registerWriteKey($resourceCode, $resourceId), Carbon::now()->format("Y/m/d H:i:s:u"));
    }

    public static function registerRead(string $resourceCode, string $resourceId, $userId, string $fingerPrint): void {
        $cacheProvider = new LaravelCacheProvider();
        $cacheProvider->put(self::registerReadKey($resourceCode, $resourceId, $userId, $fingerPrint), Carbon::now()->format("Y/m/d H:i:s:u"));
    }

    public static function isOutdated(string $resourceCode, string $resourceId, $userId, string $fingerPrint):bool {
        $cacheProvider = new LaravelCacheProvider();
        $writeTimestamp = $cacheProvider->get(self::registerWriteKey($resourceCode, $resourceId));
        $readTimestamp = $cacheProvider->get(self::registerReadKey($resourceCode, $resourceId, $userId, $fingerPrint));
        return $writeTimestamp !== null && $readTimestamp !== null && ($readTimestamp < $writeTimestamp);
    }

}
