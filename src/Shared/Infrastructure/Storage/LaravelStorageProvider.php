<?php

namespace Qalis\Shared\Infrastructure\Storage;

use Illuminate\Support\Facades\Storage;
use Qalis\Shared\Domain\Storage\StorageProvider;

class LaravelStorageProvider implements StorageProvider {

    private const SLASH = '/';
    private const BACKSLASH = '\\';

    public function put(string $fullFilePath, string $fileName, string $fileContent): void
    {
        Storage::putFileAs($fullFilePath, $fileContent, $fileName);
    }

    public function copy(string $originPath, string $storagePath, string $fileName): void
    {
        Storage::copy($originPath, $this->addEndingSlashIfNecessary($storagePath).$fileName);
    }

    private function addEndingSlashIfNecessary(string $storagePath): string {
        $slash = $storagePath[strlen($storagePath)-1];
        return ($slash === self::SLASH) ? $storagePath : $storagePath.'/';
    }

    public function link(string $fullFilePath): string
    {
        return Storage::url($fullFilePath);
    }

    public function remove(string $fullFilePath, string $fileName): void
    {
        Storage::delete($fullFilePath."/".$fileName);
    }

}
