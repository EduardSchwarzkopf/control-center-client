<?php

class BackupFile extends File
{
    static public $EXTENSION_FILE = '.tgz';
    static public $EXTENSION_DATABASE = '.sql.gz';

    public function __construct($filename)
    {
        parent::__construct(self::BackupFolder() . '/' . $filename);
    }

    static private function BackupFolder(): string
    {
        return dirname(__FILE__) . '/backups';
    }

    static private function GetBackupPattern(int $type)
    {
        $basePattern = self::BackupFolder() . '/*';
        switch ($type) {
            case 1:
                $pattern = $basePattern . self::$EXTENSION_FILE;
                break;

            case 2:
                $pattern = $basePattern . self::$EXTENSION_DATABASE;
                break;

            default:
                $pattern = $basePattern;
                break;
        }

        return $pattern;
    }

    static private function GetLatestBackup(int $type): self
    {
        $recentFilePath = FileUtils::GetLatestFileByPattern(self::GetBackupPattern($type));

        if ($recentFilePath == null) {
            return new self();
        }

        return new self($recentFilePath);
    }

    static private function GetFileList(int $type): array
    {
        $backupFileList = FileUtils::GetFileListByPattern(self::GetBackupPattern($type));

        $backupList = [];
        foreach ($backupFileList as $backupFile) {
            array_push($backupList, new BackupFile($backupFile));
        }

        return $backupList;
    }

    static public function GetFileBackupList(): array
    {
        return self::GetFileList(1);
    }

    static public function GetDatabseBackupList(): array
    {
        return self::GetFileList(2);
    }

    static public function GetLatestFileBackup(): ?self
    {
        return self::GetLatestBackup(1);
    }

    static public function GetLatestDatabaseBackup(): ?self
    {
        return self::GetLatestBackup(2);
    }

    public function DeleteBackup(): self
    {
        $result = FileUtils::DeleteFile($this->Location());
        $this->exist = $result;
        return $this;
    }
}
