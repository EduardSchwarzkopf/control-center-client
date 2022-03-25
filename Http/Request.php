<?php

class Request
{

    private string $method = '';
    private ?array $postVars = null;

    function __construct($method, $uri, $postVars = null)
    {

        $this->method = $method;
        $this->postVars = $postVars;

        $this->GetController($uri);
    }

    private function GetController(string $uri): ?ControllerInterface
    {
        $controller = null;

        // Controller anhand von URI bekommen

        return $controller;
    }

    public function All(): array
    {
        return $this->postVars;
    }

    private function GetResponse(ControllerInterface $controller): array
    {

        switch ($this->method) {
            case 'GET':
                $response = $controller->Get();
                break;

            case 'POST':
                $response = $controller->Post($this);
                break;

            case 'PUT':
                $response = $controller->Put($this);
                break;

            case 'DELETE':
                $response = $controller->Delete($this);
                break;

            default:
                $response = [];
                break;
        }

        return $response;
    }


    public function Response(): string
    {

        $list = [];

        return json_encode($list);
    }
}
