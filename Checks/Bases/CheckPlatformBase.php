<?php

abstract class CheckPlatformBase implements CheckPlatformInterface
{
    public string $db_server_info = '';

    static public function Run(Platform $platform): bool
    {

        $dbCeck = $platform->CheckDatabaseConnection();

        return $dbCeck;
    }
}
