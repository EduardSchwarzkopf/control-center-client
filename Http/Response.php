<?php

class Response extends Http
{
    public ?int $status_code = null;
    public string $message = '';

    public function __construct(int $statusCode = 200, string $message = '')
    {
        $this->status_code = $statusCode;
        $this->message = $message;
    }

    public function Add(array $list): self
    {
        parent::AddProperty($list);
        return $this;
    }

    public function JSON(): string
    {
        $vars = $this->ToArray();
        unset($vars['status_code']);
        return json_encode($vars);
    }
}
