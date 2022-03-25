<?php

class CheckBackupDatabase extends CheckBackupBase
{
    static public function Run(int $threshold, string $pattern = null): bool
    {

        return parent::Run($threshold, '*.sql.gz');
    }
}
