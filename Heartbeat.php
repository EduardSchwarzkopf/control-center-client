<?php

class Heartbeat
{
    private array $_result = [];
    private array $_info = [];
    private array $_error = [];

    public function StartChecks(array $checkList): void
    {
        // example checklist
        // $checkList = [
        //     'diskusage' => 80,
        //     'inodes' => 80,
        //     'backup_database' => 24,
        //     'backup_files' => 24,
        //     'email' => 'my@email.com',
        //     'platform' => 'wordpress',
        // ];

        foreach ($checkList as $checkItem => $value) {


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
                    } catch (Exception $e) {
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
                    $platformCheck = CheckPlatform::GetCheckPlatformObject($value);

                    if ($platform == null) {
                        Logger::Warning('Platform ' . $value . ' not found');
                        break;
                    }

                    if ($platformCheck == null) {
                        Logger::Warning('PlatformCheck ' . $value . ' not found');
                        break;
                    }

                    $result = $platformCheck::Run($platform);

                    break;
            }

            $this->_result[$checkItem] = $result;
        }
    }

    public function JSONResponse(): string
    {
        $response = [
            'result' => $this->_result,
            'error' => $this->_error,
            'info' => $this->_info
        ];

        return json_encode($response);
    }
}
