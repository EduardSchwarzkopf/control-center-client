<?php


class Magento1Platform extends Platform
{

    function __construct()
    {
        $configFilePath = '/app/etc/local.xml';
        parent::__construct($configFilePath);

        $config = $this->platformConfig->global->resources->default_setup->connection;

        $this->host = $config->host;
        $this->database = $config->dbname;
        $this->username = $config->username;
        $this->password = $config->password;
    }
}
