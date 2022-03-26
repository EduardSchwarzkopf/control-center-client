<?php

class NotFoundResponse extends Response
{
    public function __construct($message = 'Ressource not found')
    {

        $this->message = 404;
        $this->message = $message;
    }
}
