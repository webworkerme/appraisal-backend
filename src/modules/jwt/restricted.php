<?php
use \Firebase\JWT\JWT;
$jwt = $request->getHeaders();
if (isset($jwt)) {
    $key = getenv('SECRET_KEY');
    try {
        $decoded = JWT::decode($jwt['HTTP_AUTHORIZATION'][0], $key, array('HS256'));
    } catch (UnexpectedValueException $e) {
        return $this->response->withJson(array("error" => "Bad Request", "code" => "401"));
    }

    if (isset($decoded)) {
        $sql = "SELECT * FROM tokens WHERE user_id = :user_id";

        try {
            $db = $this->db;
            $stmt = $db->prepare($sql);
            $stmt->bindParam("user_id", $decoded->context->user->user_id);
            $stmt->execute();
            $user_from_db = $stmt->fetchObject();
            $db = null;

            if (isset($user_from_db->user_id)) {
                return $this->response->withJson(array("success" => "Validated", "code" => "200"));
            }
        } catch (PDOException $e) {
            return $this->response->withJson(array("error" => "Bad Request", "code" => "401"));
        }
    }
} else {
    return $this->response->withJson(array("error" => "Bad Request", "code" => "401"));
}
