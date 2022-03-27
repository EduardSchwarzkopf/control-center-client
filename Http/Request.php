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
        $this->SetResponse($controller);
    }

        $uriList = explode('/', $uri);

        $this->endpoint = $uriList[3];

        if (array_key_exists(4, $uriList)) {
            $this->extension = $uriList[4];
        }

        $controller = $this->GetController();
        $this->SetResponse($controller);
    }

    private function GetController(): ?Controller
    {
        $classname = $this->endpoint;
        $controller = Controller::GetControllerByName($classname, $this);
        return $controller;
    }

    public function PostDataList(): array
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
