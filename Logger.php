<?php

class Logger
{
    private $dir = './logs/';
    private $maxFiles = 7;

    static public function Error(Exception $error): void
    {

        $file = 'error.log';

        $message = $error->getMessage();
        $line = $error->getLine();
        $file = $error->getFile();

        $content = "ERROR: $message in $file on line $line";

        $logger = new self;
        $logger->WriteLog($content, $file);
    }


    private function WriteLog(string $content, string $file): bool
    {
        $dir = $this->dir;
        if (!is_dir($dir)) mkdir($dir);

        $now = date("Y-m-d H:i");
        $line = $now . '::' . $content;

        $result = file_put_contents("$dir/$file", $line, FILE_APPEND);

        $this->RotateLog();

        return $result;
    }

    private function RotateLog(): void
    {
        $sqlFiles = glob($this->dir . "*.{log}", GLOB_BRACE);
        FileUtils::CleanupFiles($sqlFiles, $this->maxFiles);
    }
}
