<?php

abstract class Request
{
    private $field = [];

    abstract public function Response(): string;

    public function __get($name)
    {
        if (isset($this->field[$name]))
            return $this->field[$name];
        else
            throw new Exception("$name dow not exists");
    }
}
