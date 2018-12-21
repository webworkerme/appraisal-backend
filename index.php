<?php

require 'vendor/autoload.php';

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => false
    ]
]);

require 'src/autoload.php';

$app->run();

?>
