<?php


$Heartbeat = new Heartbeat;
$Heartbeat->StartChecks($_POST);

$response = $Heartbeat->JSONResponse();

echo $response;
