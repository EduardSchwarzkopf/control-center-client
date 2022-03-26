<?php

class ApiRequest extends Request
{

    private string $method = '';
    private ?array $postVars = null;
    private array $response = [];

    function __construct(string $method, string $uri, $postVars = null)
    {

        $this->method = $method;
        $this->postVars = $postVars;

        foreach ($postVars as $key => $value) {
            $this->{$key} = $value;
        }

        $uriList = explode('/', $uri);
        $controller = $this->GetController($uriList);

        $this->SetResponse($controller);
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

    private function SetResponse(Controller $controller): void
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

        $this->response = $response;
    }


    public function Response(): string
    {

        $list = $this->response;

        return json_encode($list);
    }
}
