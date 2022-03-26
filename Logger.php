<?php

class Logger
{
    static private $DIR = './logs/';
    static private $MAX_FILES = 7;

    static public function Error(Exception $error): void
    {

        $file = 'error.log';

        $message = $error->getMessage();
        $line = $error->getLine();
        $file = $error->getFile();

        $line = "ERROR: $message in $file on line $line";

        $logger = new self;
        $logger->WriteLog($line, $file);
    }

    static public function Info($content): void
    {

        $file = 'info.log';
        $line = 'INFO: ' . $content;

        self::WriteLog($line, $file);
    }

    static public function Warning($content): void
    {
        $file = 'warning.log';
        $line = 'WARNING: ' . $content;

        self::WriteLog($line, $file);
    }

    static public function Success(string $content): void
    {
        $file = 'info.log';
        $line = 'SUCCESS: ' . $content;

        self::WriteLog($line, $file);
    }

    static private function WriteLog(string $content, string $file): bool
    {
        $dir = self::$DIR;
        FileUtils::CreateFolderIfNotExist($dir);

        $now = date("Y-m-d H:i");
        $line = $now . '::' . $content;

        $result = file_put_contents("$dir/$file", $line, FILE_APPEND);

        self::RotateLog();

        return $result;
    }


    static private function RotateLog(): void
    {
        $sqlFiles = glob(self::$DIR . "*.{log}", GLOB_BRACE);
        FileUtils::CleanupFiles($sqlFiles, self::$MAX_FILES);
    }
}
