<?php

class BackupsController extends ApiController
{
    private string $FIELD_SQL = 'database';
    private string $FIELD_FILE = 'files';

    public function Get(): Response
    {

        $response = new Response();
        $request = $this->request;
        $params = $request->Params();
        $extension = $request->Extension();

        if (empty($extension) == false) {
            $backupFile = new BackupFile($extension);

            if ($backupFile->Exist()) {
                $this->DownloadBackup($backupFile);
            } else {
                $response = new NotFoundResponse('File not found');
            }

            return $response;
        }

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

    private function DownloadBackup(?BackupFile $backupFile): void
    {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $backupFile->Name() . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $backupFile->Bytes());
        flush();
        readfile($backupFile->Location());
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

            $backupFile = $type == $this->FIELD_FILE ? BackupFile::GetLatestFileBackup() : BackupFile::GetLatestDatabaseBackup();
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

        $response = new Response(201);

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

    public function Delete(): Response
    {
        $request = $this->request;
        $params = $request->Params();

        if (key_exists('file', $params) == false) {
            return new Response(400, 'file field is required');
        }

        $backupFile = new BackupFile($params['file']);

        if ($backupFile->Exist() == false) {
            return new NotFoundResponse('File not found');
        }

        $response = new Response(204, 'Backup deleted');

        if ($backupFile->Exist() == false) {
            $response->status_code = 502;
            $response->message = 'File could not be deleted';
        }

        return $response;
    }
}
