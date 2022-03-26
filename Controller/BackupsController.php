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

        $platformName = $request->platform;

        if ($platformName == null) {
            return ['message' => 'platform required'];
        }

        $response = [];

        $platform = Platform::GetPlatformObject($platformName);

        if (property_exists($request, 'sql_dump') && $request->sql_dump) {

            $response['sql_dump'] = $platform->CreateSQLDump();
        }

        if (property_exists($request, 'file_dump') && $request->file_dump) {

            $response['file_dump'] = $platform->CreateFilesBackup(null);
        }

        return $response;
    }
}
