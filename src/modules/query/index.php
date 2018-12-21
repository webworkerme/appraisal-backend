<?php

## API Vesrion 1 Get ##
$app->group('/api/v1/get', function () use ($app) {
    $app->get('/categories', function ($request, $response, $args) {
        $conn = PDOConnection::getConnection();
        $sql = "SELECT * FROM categories";
        $sth = $conn->prepare($sql);
        $sth->execute();
        $exp = $sth->fetchAll();
        return $this->response->withJson($exp);
    });
    $app->get('/categories/[{id}]', function ($request, $response, $args) {
        $conn = PDOConnection::getConnection();
        $sql = "SELECT * FROM categories WHERE type LIKE :id";
        $sth = $conn->prepare($sql);
        $sth->bindParam("id", $args['id']);
        $sth->execute();
        $exp = $sth->fetchAll();
        return $this->response->withJson($exp);
    });
    $app->get('/experience/[{id}]', function ($request, $response, $args) {
        $conn = PDOConnection::getConnection();
        $sql = "SELECT * FROM experience WHERE id=:id";
        $sth = $conn->prepare($sql);
        $sth->bindParam("id", $args['id']);
        $sth->execute();
        $exp = $sth->fetchObject();
        return $this->response->withJson($exp);
    });
    $app->get('/experiences', function ($request, $response, $args) {
        $conn = PDOConnection::getConnection();
        $sql = "SELECT * FROM experience Order By id ASC LIMIT 0, 6";
        $sth = $conn->prepare($sql);
        $sth->execute();
        $exp = $sth->fetchAll();
        return $this->response->withJson($exp);
    });
    $app->get('/user/experience/[{id}]', function ($request, $response, $args) {
        $conn = PDOConnection::getConnection();
        $sql = "SELECT * FROM users WHERE id=:id";
        $sth = $conn->prepare($sql);
        $sth->bindParam("id", $args['id']);
        $sth->execute();
        $exp = $sth->fetchObject();
        return $this->response->withJson($exp);
    });
    $app->get('/vent', function ($request, $response, $args) {
        $conn = PDOConnection::getConnection();
        $sql = "SELECT * FROM vent As a INNER JOIN (SELECT name, image As profile_img, id As userid FROM users) AS e WHERE a.user = e.userid Order By a.id ASC LIMIT 0, 10";
        $sth = $conn->prepare($sql);
        $sth->execute();
        $exp = $sth->fetchAll();
        return $this->response->withJson($exp);
    });

    $app->get('/vent/[{id}]', function ($request, $response, $args) {
        $conn = PDOConnection::getConnection();
        $sql = "SELECT * FROM vent As a INNER JOIN (SELECT name, image As profile_img, id As userid FROM users) AS e WHERE a.user = e.userid AND a.id = :id";
        $sth = $conn->prepare($sql);
        $sth->bindParam("id", $args['id']);
        $sth->execute();
        $exp = $sth->fetchObject();
        return $this->response->withJson($exp);
    });
});
