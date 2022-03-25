<?php


class Utils
{

    static public function GetDomain(): string
    {
        $host = $_SERVER['HTTP_HOST'];
        preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches);
        return $matches[0];
    }

    public static function RandomString(int $length = 64): string
    { // 64 = 32 Chars
        $length = ($length < 4) ? 4 : $length;
        return bin2hex(random_bytes(($length - ($length % 2)) / 2));
    }

    public static function GetDiskUsage(): int
    {
        return self::GetUsage();
    }

    public static function GetInodesUsage(): int
    {
        return self::GetUsage(2);
    }

    private static function GetUsage(int $type = 1): int
    {

        if ($type == 1) {
            $command = 'df -h ./'; // diskusage
        } elseif ($type == 2) {
            $command = 'df -i /';  // inodes
        }

        $output = exec($command);

        preg_match('/ (\d+)%/', $output, $matches);

        $usage = 0.0;

        if (is_array($matches) && count($matches) > 0 && is_numeric($matches[1])) {
            $usage = floatval($matches[1]);
        }

        return $usage;
    }
}
