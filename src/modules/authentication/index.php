<?php
/**
 *  ## API Vesrion 1 Auth ##
 */
$app->group('/api/v1/auth', function () use ($app) {
    ## Create new user##
    $app->post('/create', function ($request, $response) {
        $conn = PDOConnection::getConnection();
        $input = $request->getParsedBody();
        try {
            // Check if user exists
            $check = "SELECT email FROM users WHERE email = :email";
            $sthe = $conn->prepare($check);
            $sthe->bindParam("email", $input['email']);
            $sthe->execute();
            if ($sthe->rowCount() === 1) {
                return $this->response->withJson(array("error" => "Account exist", "code" => "401"));
            } else {
                // Create new user
                $input['image'] === '' || !$input['image'] ? $image = 'default' : $image = $input['image'];
                $sql = "INSERT INTO users (name, email, password, image, updated, created) VALUES (:name, :email, :password, :image, now(), now())";
                $sth = $conn->prepare($sql);
                $sth->bindParam("name", $input['name']);
                $sth->bindParam("email", $input['email']);
                $sth->bindParam("image", $image);
                $sth->bindParam("password", password_hash($input['password'], PASSWORD_DEFAULT));
                $sth->execute();
                $input['id'] = $conn->lastInsertId();
                $input['image'] = $image;
                $input['token'] = JWTAuth::getToken($input['id'], $input['email']);
                unset($input['password']);

                /* Send Mail
                $name = $input['name'];
                $email = $input['email'];
                $subject = 'Welcome to Appraisal!';
                require 'src/libs/mails/welcome.php';
                return $this->response->withJson($input); */
            }
        } catch (PDOException $e) {
            die($this->response->withJson(array("error" => "Bad Request", "code" => "401")));
        } finally {
            $conn = null;
        }
    });
    ## Authenticate User
    $app->post('/signin', function ($request, $response) {
        $conn = PDOConnection::getConnection();
        $input = $request->getParsedBody();
        try {
            $check = "SELECT * FROM users WHERE email = :email";
            $sth = $conn->prepare($check);
            $sth->bindParam("email", $input['email']);
            $sth->execute();
            $user = $sth->fetch(PDO::FETCH_ASSOC);
            if (password_verify($input['password'], $user['password'])) {
                // Return User Object
                $user['token'] = JWTAuth::getToken($user['email'], $user['password']);
                unset($user['password']);

                // Send Mail
                $name = $user['name'];
                $email = $user['email'];
                $subject = 'Your Appraisal ID was just used to sign in to Appraisal';
                require 'src/libs/mails/login.php';

                return $this->response->withJson($user);
            } else {
                // User Credentials Invalid
                return $this->response->withJson(array("error" => "Invalid Login Credentials", "code" => "401"));
            }
        } catch (PDOException $e) {
            die($this->response->withJson(array("error" => "Bad Request", "code" => "401")));
        } finally {
            $conn = null;
        }
    });
    ## Account Recovery
    $app->put('/recover', function ($request, $response) {
        $conn = PDOConnection::getConnection();
        $input = $request->getParsedBody();
        try {
            $check = "SELECT email, id, name FROM users WHERE email = :email";
            $sthe = $conn->prepare($check);
            $sthe->bindParam("email", $input['email']);
            $sthe->execute();
            $user = $sthe->fetch(PDO::FETCH_ASSOC);

            if ($sthe->rowCount() === 1) {
                ## Recover Account
                $characters = 'b#cdfghjklm@npqrstvwx$yz0123456!789';
                $max = strlen($characters) - 1;
                for ($i = 0; $i < 8; $i++) {
                    $password .= strtoupper($characters[mt_rand(0, $max)]);
                }
                $recover = "UPDATE users SET password = :password WHERE id = :id";
                $sth = $conn->prepare($recover);
                $sth->bindParam("id", $user['id']);
                $sth->bindParam("password", password_hash($password, PASSWORD_DEFAULT));
                $sth->execute();

                // Send Mail
                $name = $user['name'];
                $email = $user['email'];
                $subject = 'Account Recovery Successful';
                require 'src/libs/mails/recover.php';

                return $this->response->withJson(array("success" => "Password Updated", "code" => "200"));

            } else {
                // Email Invalid
                return $this->response->withJson(array("error" => "Email doesn't exist", "code" => "401"));
            }
        } catch (PDOException $e) {
            die($this->response->withJson(array("error" => "Bad Request", "code" => "401")));
        } finally {
            $conn = null;
        }
    });
});
