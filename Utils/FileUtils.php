<?php


class FileUtils
{

    static public function CleanupFiles(array $files, int $files_to_keep = 30): array
    {
        $filesList = self::SortFilesByDate($files);

        $files = [];
        for ($i = 0; count($filesList) > $files_to_keep; $i++) {
            array_push($files, $filesList[$i]);
            unlink($filesList[$i]);
            unset($filesList[$i]);
        }

        return $files;
    }

    public static function HumanFileSize(int $bytes, int $decimals = 2): string
    {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }

    public static function FileSizeInGB(int $bytes): int
    {
        return round($bytes / 1024 / 1024 / 1024, 4);
    }

    public static function GetRecentFile(array $files)
    {
        $files = array_combine($files, array_map('filemtime', $files));
        arsort($files);
        $recentFile = key($files);
        return $recentFile;
    }

    public static function GetRecentFileByPattern(string $filePattern): ?string
    {
        $files = glob($filePattern);

        if (count($files) == 0) {
            return null;
        }

        $file = self::GetRecentFile($files);
        return $file;
    }

    public static function SortFilesByDate(array $files): array
    {
        usort($files, function ($x, $y) {
            return filemtime($y) < filemtime($x);
        });

        return $files;
    }

    public static function GetModificationDate(string $filePath): string
    {
        return date("Y-m-d H:i", filemtime($filePath));
    }

    public static function GetRelativeFilePath(string $currentPath, int $levelsUp = 3): string
    {
        $rootPath = dirname(__DIR__, $levelsUp) . '/';
        return str_replace($rootPath, '', $currentPath);
    }

    public static function GetAgeHours(string $date): string
    {
        $dateNow = date("Y-m-d H:i");
        $seconds = strtotime($dateNow) - strtotime($date);
        return $seconds / 60 /  60;
    }

    public static function CreateFolderIfNotExist(string $folderPath): bool
    {
        if (is_dir($folderPath) == false) {
            return mkdir($folderPath, 0755, true);
        }

        // already exist
        return false;
    }
}
