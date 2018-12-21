<?php

$app->add(new \Slim\Middleware\JwtAuthentication([
    "secret" => SECRET,
    "rules" => [
        new \Slim\Middleware\JwtAuthentication\RequestPathRule([
            "path" => "/api/v2",
            "passthrough" => ["/api/v1", "/appraisal-image-preprocessor"],
        ]),
    ],
]));
## Intro ##
$app->get('/', function () {
    echo '<div style="text-align:center; margin-top:20vh;">Welcome to Appraisal</div>';
});

## Authentication Module
require 'src/modules/authentication/index.php';

## Query Module
require 'src/modules/query/index.php';
