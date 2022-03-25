<?php


$monitor = new Monitor;
$monitor->StartChecks($_POST);

$response = $monitor->JSONResponse();

echo $response;
