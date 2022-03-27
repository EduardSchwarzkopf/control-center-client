<?php

class NotFoundResponse extends Response
{
    public function __construct($message = 'Ressource not found')
    {

        $this->status_code = 404;
        $this->message = $message;
    }
}
