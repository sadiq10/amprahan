<?php

function errorHandler($errno, $errstr, $errfile, $errline) {
    $message = "Error: [$errno] $errstr in $errfile on line $errline";
    error_log($message . PHP_EOL, 3, __DIR__ . '/../../logs/errors.log');

    if (ini_get('display_errors')) {
        echo $message;
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'An unexpected error occurred.']);
    }

    return true;
}

set_error_handler('errorHandler');
