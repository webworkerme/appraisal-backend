# Sturta Backened with JWT and Slim

## Running locally
* Clone or download the repository

* Be sure that your database configuration match the specification under /src/settings.php
* Check that all is ok by entering into the downloaded repository and launching phpunit using the following command
```
$ ./vendor/bin/phpunit
```
* You should see
```
OK (4 tests, 8 assertions)
```
* You can now launch the server by typing
```
php -S 0.0.0.0:8080 -t public public/index.php
```

* You are ready to send requests to the server. Check /tests/Functional/RoutesTest.php to see what you can do.

## Routes
Two routes were created:

* An authentication route which allows us to get the credentials and the token sent from the client for validation.
```php
$app->post('/authenticate', function (Request $request, Response $response) {
    // ...
})
```

* A route which handle a get request for requiring restricted resource to test out our JWT implementation. This route expected
that a token is set on the authorisation header of the request. The token will be validated and if it succeed, we return
the requested resource to the client.
```php
$app->get('/restricted', function (Request $request, Response $response) {
    // ...
})
```

## Dependencies
We used [firebase/php-jwt] (https://github.com/firebase/php-jwt) for creating and decoding the JSON web token.

## Storage
For simplicity sake, users credentials are stored in a JSON file named users.json located at the root of the project.
A database containing a single table named tokens allows us to store each token related information. Database
connexion is configured inside /src/dependencies.php.

## Middleware
We created a middleware under the /src/middleware.php file in order to enable CORS.
