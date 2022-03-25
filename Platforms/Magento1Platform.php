<?php


class Magento1Platform extends Platform
{

    function __construct()
    {
        $configFilePath = '/app/etc/local.xml';
        parent::__construct($configFilePath);

        $config = $this->_platformConfig->global->resources->default_setup->connection;

        $this->_host = $config->host;
        $this->_database = $config->dbname;
        $this->_username = $config->username;
        $this->_password = $config->password;
    }
}
