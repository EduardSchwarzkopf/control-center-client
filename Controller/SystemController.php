<?php

class SystemController extends ApiController
{

    public function Get(): Response
    {
        $resquest = $this->request;
        $params = $resquest->Params();

        $response = new Response();

        foreach ($params as $checkItem => $value) {

            switch ($checkItem) {
                case 'diskusage':
                    $result = Utils::GetDiskUsage();
                    break;

                case 'inodes':
                    $result = Utils::GetInodesUsage();
                    break;

                case 'platform':

                    $platform = Platform::GetPlatformObject($value);

                    if ($platform == null) {
                        Logger::Warning('Platform ' . $value . ' not found');
                        break;
                    }

                    $platform->CheckDatabaseConnection();
                    $result = $platform->ToArray();

                    break;

                default:
                    $result = 'Not supported';
                    break;
            }

            $response->SetData($checkItem, $result);
        }

        return $response;
    }
}
