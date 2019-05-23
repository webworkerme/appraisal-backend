<?php
/*
***************
 @param name: Sturta API Version 1
 @param author: Benedict Asamoah
***************
*/
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;

// Home
$app->get('/', function (Request $request, Response $response) {
    echo '<h1>๑۞๑,¸¸,ø¤º°`°๑۩ 💲🌴⛎🌱🌴🅰 ๑۩ ,¸¸,ø¤º°`°๑۞๑</h1>';
});
#######
################### JWT ##
#######
// Generate Json Web Tokens.
$app->post('/generate-tokens', function (Request $request, Response $response) {
    require 'modules/jwt/index.php';
});
/* Restricted JWT.
$app->get('/restricted', function (Request $request, Response $response) {
    require 'modules/jwt/restricted.php';
});
#######
################### Authentication ##
#######
$app->group('/api/v1/auth', function () use ($app) {
    require 'modules/auth/index.php';
});
$app->get('/test', function (Request $request, Response $response) {
    echo getenv('DBPASS');
});
*/
