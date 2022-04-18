<?php

class Request extends Http
{

    private string $method = '';
    private array $params = [];
    private string $endpoint = '';
    private string $extension = '';
    private Response $response;

    function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->params = $this->GetParams();

        $this->HandleUri($_SERVER['REQUEST_URI']);

        $controller = $this->GetController();

        if ($controller) {
            $this->SetResponse($controller);
        } else {
            $this->response = new NotFoundResponse();
        }
    }

    private function HandleUri($uri): void
    {
        $uri = explode('?', $uri)[0];

        $uriList = explode('/', $uri);
        $this->endpoint = $uriList[3];

        if (array_key_exists(4, $uriList)) {
            $this->extension = $uriList[4];
        }
    }

    public function Params(): array
    {
        return $this->params;
    }

    public function Extension(): string
    {
        return $this->extension;
    }

    private function GetParams(): array
    {
        switch ($this->method) {
            case 'GET':
                $params = $_GET;
                break;

            case 'POST':
                $params = $_POST;
                break;

            case 'PUT':
            case 'DELETE':
                parse_str(file_get_contents("php://input"), $params);
                break;

            default:
                $params = [];
                break;
        }

        return $params;
    }

    private function GetController(): ?Controller
    {
        $classname = $this->endpoint;
        $controller = Controller::GetControllerByName($classname, $this);
        return $controller;
    }

    private function SetResponse(Controller $controller): void
    {

        switch ($this->method) {
            case 'GET':
                $response = $controller->Get();
                break;

            case 'POST':
                $response = $controller->Post();
                break;

            case 'PUT':
                $response = $controller->Put();
                break;

            case 'DELETE':
                $response = $controller->Delete();
                break;

            default:
                $response = new NotFoundResponse();
                break;
        }

        $this->response = $response;
    }

    public function GetResponse(): Response
    {
        return $this->response;
    }
}
