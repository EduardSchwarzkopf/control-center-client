<?php

class Response extends Http
{
    public ?int $status_code = null;
    public string $message = '';
    public array $data = [];

    public function __construct(int $statusCode = 200, string $message = '')
    {
        $this->status_code = $statusCode;
        $this->message = $message;
    }

    public function SetData(string $field, array $data): self
    {
        $this->data[$field] = $data;
        return $this;
    }

    public function AddData(string $field, array $data): self
    {

        if (array_key_exists($field, $this->data) && is_array($this->data[$field])) {
            array_push($this->data[$field], $data);
        } else {
            $this->SetData($field, $data);
        }

        return $this;
    }

    public function JSON(): string
    {
        $vars = $this->ToArray();
        unset($vars['status_code']);
        return json_encode($vars);
    }
}
