<?php

class Monitor
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
        //     'wordpress' => true,
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

                case 'wordpress':
                case 'magento1':
                case 'magento2':

                    $platformName = ucfirst($checkItem) . 'Platform';

                    try {
                        $platform = new $platformName;
                    } catch (Exception $e) {
                        $message = $e->getMessage();
                        $line = $e->getLine();
                        $file = $e->getFile();

                        $this->_error[$platformName] = "PLATFORM ERROR: $message in $file on line $line";
                        break;
                    }

                    $platformCheck = 'Check' . $platformName;
                    $result = $platformCheck::Run($platform);

                    $serverInfo = $platform->db_server_info;
                    $this->_info['db_server_info'] = $serverInfo;

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
