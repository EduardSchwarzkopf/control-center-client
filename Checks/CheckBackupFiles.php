<?php

class CheckBackupFiles extends CheckBackupBase
{
    static public function Run(int $threshold, string $pattern = null): bool
    {

        return parent::Run($threshold, '*.tar.gz');
    }
}
