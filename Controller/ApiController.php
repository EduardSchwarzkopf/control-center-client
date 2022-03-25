<?php


abstract class ApiController implements ControllerInterface
{
    public function Get(): array
    {

        return $this->NotSupported();
    }

    public function Post(Request $request): array
    {

        return $this->NotSupported();
    }

    public function Put(Request $request): array
    {
        return $this->NotSupported();
    }

    public function Delete(Request $request): array
    {
        return $this->NotSupported();
    }

    private function NotSupported(): array
    {
        return [
            'message' => 'method not allowed'
        ];
    }
}
