<?php

  /**
   * Class PDO Connection
   */
  class PDOConnection {

    private function __construct() {}

    /**
     * This function create a database connection
     * @return Object database connection
     */
    public static function getConnection() {
      /** @var String hostname */
      $host = DB_HOST;
      /** @var String database username */
      $user = DB_USER;
      /** @var String database password */
      $pass = DB_PASS;
      /** @var String database name */
      $name = DB_NAME;
      /** @var String port number */
      $port = DB_PORT;
      /** @var String charset */
      $charset = 'utf8';

      /** @var String connection string */
      $dsn = "mysql:host={$host};port={$port};dbname={$name};charset={$charset}";

      try {
        // Create a new PDO connection
        $connection = new PDO($dsn, $user, $pass);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        // Return the connection
        return $connection;
      } catch (PDOException $e) {
        die($e);
      }
    }
  }
?>
