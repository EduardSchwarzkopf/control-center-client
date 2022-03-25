<?php


class WordpressPlatform extends Platform
{

    function __construct()
    {
        $configFilePath = '/wp-config.php';
        parent::__construct($configFilePath);

        $this->_host = DB_HOST;
        $this->_database = DB_NAME;
        $this->_username = DB_USER;
        $this->_password = DB_PASSWORD;
    }
}
