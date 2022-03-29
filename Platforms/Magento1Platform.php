<?php


class Magento1Platform extends PhpPlatform
{

    function __construct()
    {
        parent::__construct('/app/Mage.php');

        Mage::app('default');
        $config  = Mage::getConfig()->getResourceConnectionConfig('default_setup');

        $this->host = $config->host;
        $this->username = $config->username;
        $this->password = $config->password;
        $this->database = $config->dbname;

        $this->version = Mage::getVersion();
        $this->theme = [
            'name' => Mage::getDesign()->getTheme('frontend'),
        ];
    }
}
