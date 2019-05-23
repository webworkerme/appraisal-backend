<?php

use \Firebase\JWT\JWT;

// Authenticate route.
$data = $request->getParsedBody();

if ($data['authtoken'] === getenv('AUTHTOKE')) {
    $current_user['user_id'] = getenv('DYSLID');
    $current_user['user_login'] = getenv('DYSLUSER');
    $current_user['user_pwd'] = getenv('DYSLPASS');
    if (!isset($current_user)) {
        echo json_encode("No user found");
    } else {

        // Find a corresponding token.
        $sql = "SELECT * FROM tokens
            WHERE user_id = :user_id AND date_expiration >" . time();

        $token_from_db = false;
        try {
            $db = $this->db;
            $stmt = $db->prepare($sql);
            $stmt->bindParam("user_id", $current_user['user_id']);
            $stmt->execute();
            $token_from_db = $stmt->fetchObject();
            $db = null;

            if ($token_from_db) {
                echo json_encode([
                    "token" => $token_from_db->value,
                    "user_login" => $token_from_db->user_id,
                ]);
            }
        } catch (PDOException $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }

        // Create a new token if a user is found but not a token corresponding to whom.
        if (count($current_user) != 0 && !$token_from_db) {
            $key = getenv('SECRET_KEY');
            $payload = array(
                "iss" => "https://sturta.com",
                "iat" => time(),
                "exp" => time() + (3600 * 24 * 15),
                "context" => [
                    "user" => [
                        "user_login" => $current_user['user_login'],
                        "user_id" => $current_user['user_id'],
                    ],
                ],
            );

            try {
                $jwt = JWT::encode($payload, $key);
            } catch (Exception $e) {
                echo json_encode($e);
            }

            $sql = "INSERT INTO tokens (user_id, value, date_created, date_expiration)
                VALUES (:user_id, :value, :date_created, :date_expiration)";
            try {
                $db = $this->db;
                $stmt = $db->prepare($sql);
                $stmt->bindParam("user_id", $current_user['user_id']);
                $stmt->bindParam("value", $jwt);
                $stmt->bindParam("date_created", $payload['iat']);
                $stmt->bindParam("date_expiration", $payload['exp']);
                $stmt->execute();
                $db = null;

                echo json_encode([
                    "token" => $jwt,
                    "user_login" => $current_user['user_id'],
                ]);
            } catch (PDOException $e) {
                echo '{"error":{"text":' . $e->getMessage() . '}}';
            }
        }
    }
} else {
    echo $this->response->withJson(array("error" => "Bad Request", "code" => "401"));
}
