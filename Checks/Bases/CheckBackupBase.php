<?php

abstract class CheckBackupBase implements CheckFilesInterface
{

    static public function Run(int $threshold, string $pattern = null): bool
    {

        if ($pattern == null) {
            return false;
        }

        $backupfolder = dirname(__FILE__, 3) . '/backups';

        if (is_dir($backupfolder) == false) {
            mkdir($backupfolder, 0755, true);
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
