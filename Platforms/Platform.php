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

    static public function GetPlatformObject(string $platformName): ?self
    {
        $platformClassname = ucfirst($platformName) . 'Platform';
        return ClassUtils::GetClassByName($platformClassname);
    }

    protected function GetBackupFolder(): string
    {
        $backupPath = CLIENT_ROOT . '/backups';
        return $backupPath;
    }

    public function CreateSQLDump(): BackupFile
    {
        $sqlCheck = $this->CheckDatabaseConnection();


        if ($sqlCheck == false) {
            return [
                'message' => 'No Database connection',
                'result' => false
            ];
        }

        $responseList = [];
        $responseList['db_version'] = $this->db_server_info;

        $host = $this->host;
        $database = $this->database;
        $username = $this->username;
        $password = $this->password;

        $randomString = Utils::RandomString();

        $fileName = date('Y-m-d_H-i-s') . '_' . $database . '_' . $randomString . '.sql.gz';

        $backupFolder = $this->GetBackupFolder();
        FileUtils::CreateFolderIfNotExist($backupFolder);

        $dumpFilePath = $backupFolder . '/' . $fileName;

        $cmd = "mysqldump --user=$username  --password=$password  --host=$host  --routines --skip-triggers --lock-tables=false --default-character-set=utf8  $database --single-transaction=TRUE | gzip > $dumpFilePath";
        exec($cmd);

        return new BackupFile($dumpFilePath);
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
            Logger::Error($e);
            return false;
        }
    }

    //
    // Doku: Reponse ggf. modularer gestalten für die Zukunft, ist aber aufgrund von Projektbudget nicht vorgehsen
    //
    public function CreateFilesBackup(?array $exludePatternList): BackupFile
    {
        $exlude = '';
        $rootPath = dirname(__DIR__, 3);
        $platformPath = FileUtils::GetRelativeFilePath($this->platformRoot);

        $now = date('Y-m-d_H-i-s');
        $randomString = Utils::RandomString();

        $filename = $now . '_files_backup_' . $randomString . '.tgz';

        $clientPath = FileUtils::GetRelativeFilePath(CLIENT_ROOT);

        $backupFolder = $this->GetBackupFolder();
        $backupFilePath = $backupFolder . '/' . $filename;
        FileUtils::CreateFolderIfNotExist($backupFolder);

        foreach ($exludePatternList as $excludePattern) {
            $exlude .= "--exclude=$excludePattern ";
        }

        $cmd = "tar -cvz --exclude=$clientPath $exlude -C $rootPath -f $backupFilePath $platformPath";
        exec($cmd);

        return new BackupFile($backupFilePath);
    }
}
