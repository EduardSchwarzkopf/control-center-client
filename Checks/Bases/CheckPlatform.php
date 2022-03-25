<?php

abstract class CheckPlatform implements CheckPlatformInterface
{
    protected string $db_server_info = '';

    static public function Run(Platform $platform): bool
    {

        $dbCeck = $platform->CheckDatabaseConnection();

        return $dbCeck;
    }

    static public function GetCheckPlatformObject(string $platformName): ?self
    {
        $classname = 'Check' . ucfirst($platformName) . 'Platform';
        return ClassUtils::GetClassByName($classname);
    }
}
