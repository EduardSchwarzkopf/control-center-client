<?php
define('CLIENT_ROOT', dirname(__DIR__));
header('Content-Type: application/json; charset=utf-8');
include(CLIENT_ROOT . '/Autoloader.php');

$whitelist = array('127.0.0.1', "::1");
if (in_array($_SERVER['REMOTE_ADDR'], $whitelist) == false) {

    $token = JWT::getBearerToken();

    if ($token == null) {
        $requiredTokenResponseCode = 404;
        http_response_code($requiredTokenResponseCode);
        echo json_encode(['message' => 'Token required']);
        return;
    }

    $jwt = new JWT($token);
    $isValid = $jwt->isValid;

    if ($isValid == false) {
        $invalidTokenResponseCode = 401;
        http_response_code($invalidTokenResponseCode);
        echo json_encode(['message' => 'Invalid token']);
        return;
    }
}

$request = new Request();

$response = $request->GetResponse();
http_response_code($response->status_code);

echo $response->JSON();
