<?php

abstract class CheckBackupBase implements CheckFilesInterface
{

    static public function Run(int $threshold, string $pattern = null): bool
    {

        if ($pattern == null) {
            return false;
        }

        $backupfolder = CLIENT_ROOT . '/backups';

        $created = FileUtils::CreateFolderIfNotExist($backupfolder);
        if ($created) {
            return false;
        }

        $backupPattern = $backupfolder . '/' . $pattern;
        $backupFile = FileUtils::GetRecentFileByPattern($backupPattern);

        if ($backupFile == null) {
            return false;
        }

        $date = FileUtils::GetModificationDate($backupFile);
        $hours = FileUtils::GetAgeHours($date);
        $result =  $hours <= $threshold;

        return $result;
    }
}
