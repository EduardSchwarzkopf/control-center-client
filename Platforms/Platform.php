<?php

abstract class Platform extends Http
{

    protected string $host = '';
    protected string $db = '';
    protected string $username = '';
    protected string $password = '';
    protected string $platformRoot = '';
    protected $platformConfig;
    protected string $database = '';
    protected string $version = '';

    protected array $hideFields = [
        'host', 'db', 'username', 'password', 'platformRoot', 'platformConfig'
    ];

    function __construct()
    {
        $this->platformRoot = dirname(__DIR__, 2);
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

    public function CreateSQLDump(): void
    {
        $sqlCheck = $this->CheckDatabaseConnection();


        if ($sqlCheck == false) {
            return;
        }

        $host = $this->host;
        $database = $this->db;
        $username = $this->username;
        $password = $this->password;

        $randomString = Utils::RandomString();

        $filename = date('Y-m-d_H-i-s') . '_' . $database . '_' . $randomString . '.sql.gz';

        $backupFolder = $this->GetBackupFolder();
        FileUtils::CreateFolderIfNotExist($backupFolder);

        $dumpFilePath = $backupFolder . '/' . $filename;

        $cmd = "mysqldump --user=$username  --password=$password  --host=$host  --routines --skip-triggers --lock-tables=false --default-character-set=utf8  $database --single-transaction=TRUE | gzip > $dumpFilePath | /dev/null 2>&1 &";
        exec($cmd);
    }

    public function CheckDatabaseConnection(): bool
    {
        try {

            $result = true;
            $conn = new mysqli($this->host, $this->username, $this->password, $this->db);

            if ($conn->connect_error || $conn->error) {
                $result = false;
            }

            $this->database = $conn->server_info;

            $conn->close();
            return $result;
        } catch (Exception $e) {
            Logger::Error($e);
            return false;
        }
    }

    //
    // Doku: Reponse ggf. modularer gestalten f??r die Zukunft, ist aber aufgrund von Projektbudget nicht vorgesehen
    // Doku: Problem k??nnte ein zu langer Prozess sein - ggf. anpassen mit '...> /dev/null 2>&1 &' im exec
    //
    public function CreateFilesBackup(array $exludePatternList = []): void
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

        $cmd = "tar -cvz --exclude=$clientPath $exlude -C $rootPath -f $backupFilePath $platformPath > /dev/null 2>&1 &";
        exec($cmd);
    }
}
