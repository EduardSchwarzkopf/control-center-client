<?php
define('CLIENT_ROOT', dirname(__DIR__));
header('Content-Type: application/json; charset=utf-8');
include(CLIENT_ROOT . '/Autoloader.php');

$whitelist = array('127.0.0.1', "::1");
if (in_array($_SERVER['REMOTE_ADDR'], $whitelist) == false) {

    $token = JWT::getBearerToken();
    $response = new Response();

    if ($token == null) {
        $response->status_code = 404;
        http_response_code($response->status_code);
        $response->message =  'Token required';
        return $response->JSON();
    }

    $jwt = new JWT($token);
    $isValid = $jwt->isValid;

    if ($isValid == false) {
        $response->status_code = 401;
        http_response_code($response->status_code);
        $response->message =  'Invalid token';
        return $response->JSON();
    }
}

$request = new Request();

$response = $request->GetResponse();
http_response_code($response->status_code);

$response->JSON();
