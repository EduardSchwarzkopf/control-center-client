<?php


class Magento1Platform extends PhpPlatform
{

    function __construct()
    {
        parent::__construct();

        include($this->platformRoot . '/app/Mage.php');

        Mage::app('default');
        $config  = Mage::getConfig()->getResourceConnectionConfig('default_setup');

        $this->host = $config->host;
        $this->username = $config->username;
        $this->password = $config->password;
        $this->db = $config->dbname;

        $this->version = Mage::getVersion();
        $this->theme = [
            'name' => Mage::getDesign()->getTheme('frontend'),
        ];
    }
}
