<?php

class HeartbeatController extends ApiController
{
    // example Request
    //     'diskusage' => 80,
    //     'inodes' => 80,
    //     'backup_database' => 24,
    //     'backup_files' => 24,
    //     'email' => 'my@email.com',
    //     'platform' => 'wordpress',

    public function Post(): Response
    {
        $resquest = $this->request;
        $params = $resquest->Params();

        $heartbeat = new HeartbeatResponse();

        foreach ($params as $checkItem => $value) {

            switch ($checkItem) {
                case 'diskusage':
                    $result = CheckDiskUsage::Run($value);
                    break;

                case 'inodes':
                    $result = CheckInodes::Run($value);
                    break;

                case 'backup_database':
                case 'backup_files':

                    $stringList = explode('_', $checkItem);
                    $checkClassname = 'Check';

                    foreach ($stringList as $string) {
                        $checkClassname .= ucfirst($string);
                    }

                    try {
                        $backupCheck = new $checkClassname;
                    } catch (Error $e) {
                        $message = $e->getMessage();
                        $line = $e->getLine();
                        $file = $e->getFile();

                        $this->_error[$checkClassname] = "BACKUP_CHECK ERROR: $message in $file on line $line";
                        break;
                    }

                    $result = $backupCheck::Run($value);

                    break;

                case 'email':

                    $result = CheckEmail::Run($value);
                    break;

                case 'platform':

                    $platform = Platform::GetPlatformObject($value);

                    if ($platform == null) {
                        Logger::Warning('Platform ' . $value . ' not found');
                        break;
                    }

                    $platformCheck = CheckPlatform::GetCheckPlatformObject($value);
                    if ($platformCheck == null) {
                        Logger::Warning('PlatformCheck ' . $value . ' not found');
                        break;
                    }

                    $result = $platformCheck::Run($platform);

                    break;

                default:
                    $result = 'Not supported';
                    break;
            }

            $heartbeat->SetData($checkItem, $result);
        }

        return $heartbeat;
    }
}
