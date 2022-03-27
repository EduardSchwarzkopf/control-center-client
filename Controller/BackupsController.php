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

        if (property_exists($request, 'platform') == false && $request->platform) {
            return new Response(400, 'platform field is required');
        }

        $platformName = $request->platform;
        $resultList = [];

        $platform = Platform::GetPlatformObject($platformName);

        if ($platform == null) {
            return new NotFoundResponse('platform not found');
        }

        if (property_exists($request, 'sql_dump') && $request->sql_dump) {

            $sqlFile = $platform->CreateSQLDump();
            $resultList['sql_dump'] = $this->CreateResultListFromFileObject($sqlFile);
        }

        if (property_exists($request, 'file_dump') && $request->file_dump) {

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
