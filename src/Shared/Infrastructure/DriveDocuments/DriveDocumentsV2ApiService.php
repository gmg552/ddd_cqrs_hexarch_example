<?php

namespace Qalis\Shared\Infrastructure\DriveDocuments;

use Exception;
use Illuminate\Support\Facades\URL;
use Qalis\Shared\Domain\DriveDocuments\DriveDocumentsService;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

final class DriveDocumentsV2ApiService implements DriveDocumentsService {

    private const FOLDER_TYPE = 'application/vnd.google-apps.folder';
    private const FILE_TYPE = 'application/vnd.google-apps.document';

    public function folderExist(string $folderId): bool
    {
        $client = $this->getClient();
        $driveService = new Google_Service_Drive($client);

        try {
            $file = $driveService->files->get($folderId, [
                'supportsAllDrives' => true
            ]);
            return $file->mimeType == self::FOLDER_TYPE;
        } catch (Exception $e) {
            return false;
        }
    }

    public function fileExist(string $fileId): bool
    {
        $client = $this->getClient();
        $driveService = new Google_Service_Drive($client);

        try {
            $file = $driveService->files->get($fileId, [
                'supportsAllDrives' => true
            ]);
            return $file->mimeType == self::FILE_TYPE;
        } catch (Exception $e) {
            return false;
        }
    }

    public function createFolder(string $folderName, string $destinationFolderId): GoogleServiceDriveFileResponse
    {
        $client = $this->getClient();
        $driveService = new Google_Service_Drive($client);

        $fileMetadata = new Google_Service_Drive_DriveFile([
            'name' => $folderName,
            'mimeType' => self::FOLDER_TYPE,
            'driveId' => $destinationFolderId,
            'parents' => array($destinationFolderId)
        ]);

        $folder = $driveService->files->create($fileMetadata, [
            'fields' => 'id,name',
            'supportsAllDrives' => true
        ]);

        return new GoogleServiceDriveFileResponse($folder->id, $folder->name);
    }

    public function create(string $fileContent, string $destinationFileName, string $folderId): GoogleServiceDriveFileResponse
    {
        $client = $this->getClient();
        $driveService = new Google_Service_Drive($client);
        $fileMetadata = new Google_Service_Drive_DriveFile([
            'name' => $destinationFileName,
            'parents' => array($folderId)
        ]);

        $file = $driveService->files->create($fileMetadata, [
            'data' => $fileContent,
            'uploadType' => 'multipart',
            'fields' => 'id,name',
            'supportsAllDrives' => true
        ]);

        return new GoogleServiceDriveFileResponse($file->id, $file->name);
    }


    private function getClient(): Google_Client {

        $client = new Google_Client();
        $client->setAuthConfig(base_path('client_secret.json'));
        $client->setIncludeGrantedScopes(true);   // incremental auth
        $client->addScope(Google_Service_Drive::DRIVE);
        $client->addScope(Google_Service_Drive::DRIVE_FILE);
        $client->addScope(Google_Service_Drive::DRIVE_METADATA);
        $client->addScope(Google_Service_Drive::DRIVE_APPDATA);

        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $client->setRedirectUri('http://' . URL::to('/') . '/oauth2callback.php');

        // Load previously authorized credentials from a file.
        $credentialsPath = $this->expandHomeDirectory(base_path('credentials.json'));

        if (file_exists($credentialsPath)) {
            $accessToken = json_decode(file_get_contents($credentialsPath), true);
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));
            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

            // Store the credentials to disk.
            if (!file_exists(dirname($credentialsPath))) {
                mkdir(dirname($credentialsPath), 0700, true);
            }
            file_put_contents($credentialsPath, json_encode($accessToken));
            printf("Credentials saved to %s\n", $credentialsPath);
        }
        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
        }

        return $client;

    }

    private function expandHomeDirectory($path)
    {
        return str_replace('~', realpath(base_path()), $path);
    }

}
