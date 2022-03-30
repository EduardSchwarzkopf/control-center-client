<?php


class Magento2Platform extends PhpPlatform
{

    function __construct()
    {

        parent::__construct();
        $configList = $this->platformRoot . '/app/etc/env.php';

        $credentials = $configList["db"]['connection']['default'];
        $this->host = $credentials["host"];
        $this->database = $credentials["dbname"];
        $this->username = $credentials["username"];
        $this->password = $credentials["password"];
    }
}
