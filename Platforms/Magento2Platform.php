<?php

use Magento\Framework\App\ObjectManager;

class Magento2Platform extends PhpPlatform
{

    function __construct()
    {

        parent::__construct();
        $root = $this->platformRoot;
        $configList = include($root . '/app/etc/env.php');

        $credentials = $configList["db"]['connection']['default'];
        $this->host = $credentials["host"];
        $this->database = $credentials["dbname"];
        $this->username = $credentials["username"];
        $this->password = $credentials["password"];

        $versionOutput = exec("php $root/bin/magento --version");
        $this->version = str_replace('Magento CLI ', '', $versionOutput);
    }
}
