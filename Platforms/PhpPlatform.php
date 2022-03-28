<?php


class PhpPlatform extends Platform
{

    function __construct(?string $configPath)
    {

        parent::__construct();
        if ($configPath) {

            $this->LaodConfig($configPath);
        }

        $this->php_version = phpversion();
    }
}
