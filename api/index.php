<?php
define('__ROOT__', dirname(__FILE__, 2));
include(__ROOT__ . '/Autoloader.php');

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


$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$postVars = $_POST;


if ($method == 'PUT') {
    $postVars = null;
    parse_str(file_get_contents("php://input"), $postVars);
}


$request = new Request($method, $uri, $postVars);

$response = $request->Response();

echo $response;
