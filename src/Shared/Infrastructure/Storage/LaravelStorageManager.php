<?php

namespace Qalis\Shared\Infrastructure\Storage;

use Illuminate\Support\Facades\Storage;
use Qalis\Shared\Domain\Storage\StorageManager;

class LaravelStorageManager implements StorageManager {

    public function msWordTemplatePath(string $fileId): string
    {
        return Storage::disk('local')->path($fileId);
    }

}
