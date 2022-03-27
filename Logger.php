<?php

class Logger
{
    static private $DIR = CLIENT_ROOT . '/logs';
    static private $MAX_FILES = 7;

    static public function Error(Throwable $error): void
    {

        $logfile = 'error.log';

        $message = $error->getMessage();
        $line = $error->getLine();
        $file = $error->getFile();

        $class = strtoupper(get_class($error));
        $line = "$class: $message in $file on line $line";

        $logger = new self;
        $logger->WriteLog($line, $logfile);
    }

    static public function Info($content): void
    {

        $logfile = 'info.log';
        $line = 'INFO: ' . $content;

        self::WriteLog($line, $logfile);
    }

    static public function Warning($content): void
    {
        $logfile = 'warning.log';
        $line = 'WARNING: ' . $content;

        self::WriteLog($line, $logfile);
    }

    static public function Success(string $content): void
    {
        $logfile = 'info.log';
        $line = 'SUCCESS: ' . $content;

        self::WriteLog($line, $logfile);
    }

    static private function WriteLog(string $content, string $logfile): bool
    {
        $dir = self::$DIR;
        FileUtils::CreateFolderIfNotExist($dir);

        $now = date('Y-m-d H:i:s');
        $today = date('Y-m-d');
        $line = $now . '::' . $content;

        $result = file_put_contents("$dir/$today-$logfile", $line . "\n", FILE_APPEND);

        self::RotateLog();

        return $result;
    }


    static private function RotateLog(): void
    {
        $sqlFiles = glob(self::$DIR . "*.{log}", GLOB_BRACE);
        FileUtils::CleanupFiles($sqlFiles, self::$MAX_FILES);
    }
}
