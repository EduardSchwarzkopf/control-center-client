<?php


class Magento2Platform extends Platform
{

    function __construct()
    {

        $configPath = '/app/etc/env.php';
        parent::__construct($configPath);

        $credentials = $this->platformConfig["db"]['connection']['default'];
        $this->host = $credentials["host"];
        $this->database = $credentials["dbname"];
        $this->username = $credentials["username"];
        $this->password = $credentials["password"];
    }
}
