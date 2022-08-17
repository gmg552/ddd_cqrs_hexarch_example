<?php

namespace Qalis\Shared\Domain\Storage;

interface StorageProvider {
    public function copy(string $originPath, string $storagePath, string $fileName) : void;
    public function link(string $fullFilePath) : string;
    public function put(string $fullFilePath, string $fileName, string $fileContent): void;
    public function remove(string $fullFilePath, string $fileName): void;
}
