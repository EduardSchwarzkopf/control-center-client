<?php


abstract class ApiController extends Controller
{
    public function Get(): Response
    {

        return $this->MethodNotAllowed();
    }

    public function Post(Request $request): Response
    {

        return $this->MethodNotAllowed();
    }

    public function Put(Request $request): Response
    {
        return $this->MethodNotAllowed();
    }

    public function Delete(Request $request): Response
    {
        return $this->MethodNotAllowed();
    }

    protected function MethodNotAllowed(): Response
    {
        return new Response(405, 'Method now allowed');
    }
}
