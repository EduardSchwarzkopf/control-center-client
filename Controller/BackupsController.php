<?php

class BackupsController extends ApiController
{
    private string $FIELD_SQL = 'sql';
    private string $FIELD_FILE = 'file';

    public function Get(): Response
    {

        $response = new Response();
        $request = $this->request;
        $params = $request->Params();

        if (key_exists($this->FIELD_SQL, $params)) {
            $getAll = $params[$this->FIELD_SQL] == 'all';
            $this->SetResponseData($response, $this->FIELD_SQL, $getAll);
        }

        if (key_exists($this->FIELD_FILE, $params)) {
            $getAll = $params[$this->FIELD_FILE] == 'all';
            $this->SetResponseData($response, $this->FIELD_FILE, $getAll);
        }

        return $response;
    }

    private function SetResponseData(Response &$response, $type, $getAll = false): void
    {
        if ($getAll) {
            $backupFileList = $type == 1 ? BackupFile::GetFileBackupList() : BackupFile::GetDatabseBackupList();
            $fileList = [];
            foreach ($backupFileList as $file) {
                array_push($fileList, $file->ToArray());
            }

            $response->AddData($type, $fileList);
        } else {

            $backupFile = $type == 1 ? BackupFile::GetLatestFileBackup() : BackupFile::GetLatestDatabaseBackup();
            $response->SetData(
                $type,
                $backupFile->ToArray()
            );
        }
    }

    public function Post(): Response
    {
        $request = $this->request;
        $params = $request->Params();

        if (key_exists('platform', $params) == false) {
            return new Response(400, 'platform field is required');
        }

        $platformName = $params['platform'];

        $platform = Platform::GetPlatformObject($platformName);

        if ($platform == null) {
            return new NotFoundResponse('platform not found');
        }

        $response = new Response();

        if (key_exists($this->FIELD_SQL, $params)) {

            $sqlFile = $platform->CreateSQLDump();
            $response->AddData($this->FIELD_SQL, $sqlFile->ToArray());
        }

        if (key_exists($this->FIELD_FILE, $params)) {

            $dumpFile = $platform->CreateFilesBackup();
            $response->AddData($this->FIELD_FILE, $dumpFile->ToArray());
        }

        return $response;
    }
}
