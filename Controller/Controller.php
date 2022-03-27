<?php

abstract class Controller
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    abstract public function Get(): Response;
    abstract public function Post(): Response;
    abstract public function Put(): Response;
    abstract public function Delete(): Response;

    static public function GetControllerByName(string $name, Request $request)
    {
        $classname = $name . 'Controller';
        return ClassUtils::GetClassByName($classname, [$request]);
    }
}
