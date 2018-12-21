<?php

require 'vendor/autoload.php';
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => false
    ]
]);

require 'src/autoload.php';

$app->run();

?>
