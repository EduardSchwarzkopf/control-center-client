<?php


class WordpressPlatform extends PhpPlatform
{

    function __construct()
    {
        $configFilePath = '/wp-config.php';
        parent::__construct($configFilePath);

        $this->host = DB_HOST;
        $this->database = DB_NAME;
        $this->username = DB_USER;
        $this->password = DB_PASSWORD;
        $this->version = get_bloginfo('version');
    }
}
