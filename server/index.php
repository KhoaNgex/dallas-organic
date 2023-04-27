<?php

declare(strict_types=1);

session_start();

require __DIR__ . "/core/init.php";

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Methods: DELETE");

$myapp = new App;
$myapp->processAPI();