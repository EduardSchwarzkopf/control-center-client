<?php

class BackupsController extends ApiController
{
    public function Get(): array
    {

        $backupFile = BackupFile::GetRecentBackupFile();

        if ($backupFile == null) {
            return ['message' => 'No backup found.'];
        }

        return $backupFile->ToArray();
    }

    public function Post(Request $request): array
    {
        if (property_exists($request, 'platform') == false && $request->platform) {
            return ['message' => 'platform required'];
        }

        $platformName = $request->platform;
        $response = [];

        $platform = Platform::GetPlatformObject($platformName);

        if ($platform == null) {
            return [
                'message' => 'platform not found'
            ];
        }

        if (property_exists($request, 'sql_dump') && $request->sql_dump) {

            $response['sql_dump'] = $platform->CreateSQLDump();
        }

        if (property_exists($request, 'file_dump') && $request->file_dump) {

            $response['file_dump'] = $platform->CreateFilesBackup(null);
        }

        return $response;
    }
}
