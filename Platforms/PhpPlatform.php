<?php


class PhpPlatform extends Platform
{

    function __construct()
    {

        parent::__construct();
        $this->php_version = phpversion();
    }
}
