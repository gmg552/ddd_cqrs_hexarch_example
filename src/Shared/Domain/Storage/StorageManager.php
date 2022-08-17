<?php

namespace Qalis\Shared\Domain\Storage;


interface StorageManager {
    public function msWordTemplatePath(string $fileId) : string;
}
