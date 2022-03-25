<?php

abstract class Platform
{

    protected string $host = '';
    protected string $database = '';
    protected string $username = '';
    protected string $password = '';
    protected string $platformRoot = '';
    protected $platformConfig = '';

    function __construct($configFilePath)
    {
        $this->platformRoot = dirname(__DIR__, 2);

        $configPath = $this->platformRoot . $configFilePath;
        $this->platformConfig = $this->LoadPlatformConfigFile($configPath);
    }

    private function LoadPlatformConfigFile(string $configFilePath)
    {
        $platformConfig = include_once($configFilePath);

        if ($platformConfig == false) {
            $platformConfig = simplexml_load_file($this->platformRoot . '/app/etc/local.xml');

            if ($platformConfig == false) {
                throw new Exception($configFilePath . ' not found');
            }
        }

        return $platformConfig;
    }

    private function GetResponseList(): array
    {
        return [
            'result'=>false,
            'bytes'=>0,
            'path'=>null,
            'size'=>0,
        ];
    }

    public function CreateSQLDump(): array
    {
        $sqlCheck = $this->CheckDatabaseConnection();

        $responseList = $this->GetResponseList();
        $responseList['db_version'] = $this->db_server_info;

        if ($sqlCheck == false) {
            return $responseList;
        }

        $host = $this->host;
        $database = $this->database;
        $username = $this->username;
        $password = $this->password;

        $randomString = Utils::RandomString();

        $fileName = date('Y-m-d_H-i-s') . '_' . $database . '_' . $randomString . '.sql.gz';

        $dumpFilePath = dirname(__DIR__) . '/backups/' . $fileName;
        $cmd = "mysqldump --user=$username  --password=$password  --host=$host  --routines --skip-triggers --lock-tables=false --default-character-set=utf8  $database --single-transaction=TRUE | gzip > $dumpFilePath";
        exec($cmd);

        if (file_exists($dumpFilePath)) {

            $bytes = filesize($dumpFilePath);
            $responseList['result'] = true;
            $responseList['path'] = str_replace($this->platformRoot, '', $dumpFilePath);
            $responseList['bytes'] = $bytes;
            $responseList['size'] = FileUtils::HumanFileSize($bytes);
        }

        return $responseList;
    }

    public function CheckDatabaseConnection(): bool
    {
        try {

            $result = true;
            $conn = new mysqli($this->host, $this->username, $this->password, $this->database);

            if ($conn->connect_error || $conn->error) {
                $result = false;
            }

            $this->db_server_info = $conn->server_info;

            $conn->close();
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    //
    // Doku: Reponse ggf. modularer gestalten für die Zukunft, ist aber aufgrund von Projektbudget nicht vorgehsen
    //
    public function CreateFilesBackup(?array $exludePatternList): array
    {
        $responseList = $this->GetResponseList();

        $exlude = '';
        $rootPath = dirname(__DIR__, 3);
        $platformPath = $this->GetRelativeFilePath($this->platformRoot);

        $now = date('Y-m-d_H-i-s');
        $randomString = Utils::RandomString();

        $file = $now . '_files_backup_' . $randomString . '.tgz';

        $absoluteClientPath = dirname(__DIR__);

        $clientPath = $this->GetRelativeFilePath($absoluteClientPath);
        $backupPath = $absoluteClientPath . '/backups/' . $file;

        foreach ($exludePatternList as $excludePattern) {
            $exlude .= "--exclude=$excludePattern ";
        }

        $cmd = "tar -cvz --exclude=$clientPath $exlude -C $rootPath -f $backupPath $platformPath";
        exec($cmd);

        if (file_exists($backupPath)) {

            $bytes = filesize($backupPath);
            $responseList['result'] = true;
            $responseList['path'] = str_replace($this->platformRoot, '', $backupPath);
            $responseList['bytes'] = $bytes;
            $responseList['size'] = FileUtils::HumanFileSize($bytes);
        }

        return $responseList;
    }

    protected function GetRelativeFilePath($filePath) {
        $rootPath = dirname(__DIR__, 3) . '/';
        return str_replace($rootPath, '', $filePath);
    }
}
