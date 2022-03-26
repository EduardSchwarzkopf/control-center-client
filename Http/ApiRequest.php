<?php

class ApiRequest extends Request
{

    private string $method = '';
    private ?array $postVars = null;
    private ?object $controller = null;

    function __construct(string $method, string $uri, $postVars = null)
    {

        $this->method = $method;
        $this->postVars = $postVars;

        $uriList = explode('/', $uri);
        $this->controller = $this->GetController($uriList);
    }

    private function GetController(array $uriList): ?Controller
    {
        $classname = $uriList[3];
        $controller = Controller::GetControllerByName($classname);
        return $controller;
    }

    public function All(): array
    {
        return $this->postVars;
    }

    private function GetResponse(Controller $controller): array
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
