<?php
include(dirname(__FILE__, 2) . '/Autoloader.php');

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

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$uriList = explode('/', $uri);

$postVars = $_POST;

if ($method == 'PUT') {
    $postVars = null;
    parse_str(file_get_contents("php://input"), $postVars);
}


$request = new Request($method, $uri, $postVars);

$response = $request->Response();

echo $response;
