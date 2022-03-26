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

            $sqlFile = $platform->CreateSQLDump();
            $response['sql_dump'] = $this->CreateResponseListFromFileObject($sqlFile);
        }

        if (property_exists($request, 'file_dump') && $request->file_dump) {

            $dumpFile = $platform->CreateFilesBackup(null);
            $response['file_dump'] = $this->CreateResponseListFromFileObject($dumpFile);
        }

        return $response;
    }

    private function CreateResponseListFromFileObject(File $file)
    {
        $responseList = [];
        $responseList = $file->ToArray();

        $responseList['result'] = $file->Exist();
        unset($responseList['exist']);

        return $responseList;
    }
}
