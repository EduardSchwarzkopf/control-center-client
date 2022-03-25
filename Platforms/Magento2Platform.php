<?php


class Magento2Platform extends Platform
{

    function __construct()
    {

        $configPath = '/app/etc/env.php';
        parent::__construct($configPath);

        $credentials = $this->_platformConfig["db"]['connection']['default'];
        $this->_host = $credentials["host"];
        $this->_database = $credentials["dbname"];
        $this->_username = $credentials["username"];
        $this->_password = $credentials["password"];
    }
}
