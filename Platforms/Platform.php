<?php

abstract class Platform
{

    protected string $_host = '';
    protected string $_database = '';
    protected string $_username = '';
    protected string $_password = '';
    protected string $_platformRoot = '';
    protected $_platformConfig = '';

    protected bool $_db_result = false;
    protected string $_db_server_info = '';
    protected string $_db_dump_path = '';
    protected int $_db_file_size = 0;
    protected string $_db_human_file_size = '';

    protected bool $_backup_result = false;
    protected string $_backup_dump_path = '';
    protected int $_backup_file_size = 0;
    protected string $_backup_human_file_size = '';


    function __construct($configFilePath)
    {
        $this->_platformRoot = dirname(__FILE__, 3);

        $configPath = $this->_platformRoot . $configFilePath;
        $this->_platformConfig = $this->LoadPlatformConfigFile($configPath);
    }

    private function LoadPlatformConfigFile(string $configFilePath)
    {
        $platformConfig = include_once($configFilePath);

        if ($platformConfig == false) {
            $platformConfig = simplexml_load_file($this->_platformRoot . '/app/etc/local.xml');

            if ($platformConfig == false) {
                throw new Exception($configFilePath . ' not found');
            }
        }

        return $platformConfig;
    }

    public function GetBackupDumpPath(): string
    {
        return $this->_backup_dump_path;
    }

    public function GetBackupFileSize(): int
    {
        return $this->_backup_file_size;
    }

    public function GetBackupHumanFileSize(): string
    {
        return $this->_backup_human_file_size;
    }

    public function GetBackupResult(): bool
    {
        return $this->_backup_result;
    }

    public function GetSQLDumpPath(): string
    {
        return $this->_db_dump_path;
    }

    public function GetDatabaseFileSize(): int
    {
        return $this->_db_file_size;
    }

    public function GetDatabaseHumanFileSize(): string
    {
        return $this->_db_human_file_size;
    }

    public function GetDabaseResult(): bool
    {
        return $this->_db_result;
    }

    public function GetDatabaseInfo(): string
    {
        return $this->_db_server_info;
    }

    public function CreateSQLDump(): bool
    {
        $sqlCheck = $this->CheckDatabaseConnection();

        if ($sqlCheck == false) {
            return false;
        }

        $host = $this->_host;
        $database = $this->_database;
        $username = $this->_username;
        $password = $this->_password;

        $randomString = Utils::RandomString();

        $fileName = date('Y-m-d_H-i-s') . '_' . $database . '_' . $randomString . '.sql.gz';

        $dumpfile = dirname(__FILE__, 2) . '/backups/' . $fileName;
        $cmd = "mysqldump --user=$username  --password=$password  --host=$host  --routines --skip-triggers --lock-tables=false --default-character-set=utf8  $database --single-transaction=TRUE | gzip > $dumpfile";
        exec($cmd);

        $result = file_exists($dumpfile);

        if ($result) {

            $this->_db_dump_path = str_replace($this->_platformRoot, '', $dumpfile);
            $this->_db_file_size = filesize($dumpfile);
            $this->_db_human_file_size = FileUtils::HumanFileSize($this->_db_file_size);
        }

        $this->_db_result = $result;

        return $result;
    }

    public function CheckDatabaseConnection(): bool
    {
        try {

            $result = true;
            $conn = new mysqli($this->_host, $this->_username, $this->_password, $this->_database);

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

    public function CreateFilesBackup(array $exludeFolderList = []): bool
    {

        $exlude = '';
        foreach ($exludeFolderList as $excludeFolder) {
            $exlude .= "--exclude=$excludeFolder ";
        }

        $now = date('Y-m-d_H-i-s');
        $randomString = Utils::RandomString();

        $file = $now . '_files_backup_' . $randomString . '.tgz';

        $backupFolder = dirname(__FILE__, 2);
        $backupTarget = $this->_platformRoot;
        $backupPath = $backupFolder . '/backups/' . $file;
        $cmd = "tar zcv --exclude=$backupFolder $exlude -f $backupPath $backupTarget";
        $exec = exec($cmd, $out, $oky);

        $result = file_exists($backupPath);

        if ($result) {

            $this->_backup_dump_path = str_replace($this->_platformRoot, '', $file);
            $this->_backup_file_size = filesize($file);
            $this->_backup_human_file_size = FileUtils::HumanFileSize($this->_backup_file_size);
        }

        $this->_backup_result = $result;

        return $result;
    }
}
