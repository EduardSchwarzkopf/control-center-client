<?php

class BackupFile extends File
{

    static private function BackupFolder(): string
    {
        return dirname(__FILE__) . '/backups';
    }

    static private function GetRecentBackup(string $pattern): ?self
    {
        $backupPattern = self::BackupFolder() . $pattern;
        $recentFilePath = FileUtils::GetRecentFileByPattern($backupPattern);

        if ($recentFilePath == null) {
            return null;
        }

        return new self($recentFilePath);
    }

    static public function GetRecentBackupFile(): ?self
    {
        return self::GetRecentBackup('/*.tgz');
    }

    static public function GetRecentDatabaseBackup(): ?self
    {
        return self::GetRecentBackup('/*.sql.gz');
    }
}
