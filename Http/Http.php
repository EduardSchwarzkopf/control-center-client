<?php

abstract class Http
{
    private array $field = [];
    protected array $hideFields = [];
    private array $privateFields = ['field', 'hideFields', 'privateFields'];

    public function __get($name)
    {
        if (isset($this->field[$name]))
            return $this->field[$name];
        else
            throw new Exception("$name dow not exists");
    }

    protected function AddProperty(array $list): void
    {
        foreach ($list as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function ToArray(): array
    {
        $vars = get_object_vars($this);

        $hideFields = array_merge($this->hideFields, $this->privateFields);
        foreach ($hideFields as $field) {
            unset($vars[$field]);
        }
        return $vars;
    }
}
