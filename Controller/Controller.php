<?php

abstract class Controller
{
    abstract public function Get(): Response;
    abstract public function Post(Request $request): Response;
    abstract public function Put(Request $request): Response;
    abstract public function Delete(Request $request): Response;

    static public function GetControllerByName(string $name)
    {
        $classname = $name . 'Controller';
        return ClassUtils::GetClassByName($classname);
    }
}
