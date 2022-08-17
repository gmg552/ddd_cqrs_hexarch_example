<?php

namespace Qalis\Shared\Domain\DriveDocuments;

use Qalis\Shared\Infrastructure\DriveDocuments\GoogleServiceDriveFileResponse;

interface DriveDocumentsService {
    public function create(string $fileContent, string $destinationFileName, string $folderId) : GoogleServiceDriveFileResponse;
    public function createFolder(string $folderName, string $destinationFolderId) : GoogleServiceDriveFileResponse;
    public function folderExist(string $folderId): bool;
    public function fileExist(string $fileId): bool;
}
