<?php

class BackupsController extends ApiController
{
    public function Get(): Response
    {

        //TODO: Get all Backupfiles
        $backupFile = BackupFile::GetRecentBackupFile();

        if ($backupFile == null) {
            return new NotFoundResponse();
        }

        $backupResultList = $backupFile->ToArray();
        $response = new Response();
        $response->Add($backupResultList);

        return $response;
    }

    public function Post(): Response
    {
        $request = $this->request;
        $params = $request->Params();

        if (key_exists('platform', $params) == false) {
            return new Response(400, 'platform field is required');
        }

        $platformName = $params['platform'];
        $resultList = [];

        $platform = Platform::GetPlatformObject($platformName);

        if ($platform == null) {
            return new NotFoundResponse('platform not found');
        }

        if (key_exists('sql_dump', $params)) {

            $sqlFile = $platform->CreateSQLDump();
            $resultList['sql_dump'] = $this->CreateResultListFromFileObject($sqlFile);
        }

        if (key_exists('platform', $params)) {

            $dumpFile = $platform->CreateFilesBackup();
            $resultList['file_dump'] = $this->CreateResultListFromFileObject($dumpFile);
        }

        $response = new Response();
        $response->Add($resultList);
        return $response;
    }

    private function CreateResultListFromFileObject(File $file)
    {
        $responseList = [];
        $responseList = $file->ToArray();

        $responseList['result'] = $file->Exist();
        unset($responseList['exist']);

        return $responseList;
    }
}
