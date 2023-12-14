<?php

namespace App\Database;

use PDO;
use Dotenv\Dotenv;

class Database
{
    private $host;
    private $user;
    private $pass;
    private $dbname;
    private $charset = 'utf8mb4';
    private $dbh;
    private $error;

    private static $instance;
    private $connection;

    public function __construct()
    {
        //////////////////////////////////////////////////////////////////////////

        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        // Set database connection parameters
        $this->host = $_ENV['DB_HOST'];
        $this->user = $_ENV['DB_USERNAME'];
        $this->pass = $_ENV['DB_PASSWORD'];
        $this->dbname = $_ENV['DB_DATABASE'];

        ////////////////////////////////////////////////////////////////////////

        //HEROKU CONNECTION ///

        $host = getenv('DB_HOST');
        $dbname = getenv('DB_DATABASE');
        $user = getenv('DB_USERNAME');
        $pass = getenv('DB_PASSWORD');
        $charset = 'utf8mb4';

        ////////////////////////////////////////////////////////////////////////

        // Set DSN
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=" . $this->charset;

        // Set options
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        // Create a new PDO instance
        try {
            // Votre code d'initialisation de la connexion à la base de données ici
            $this->connection = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->user, $this->pass, $options);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Gérer les erreurs de connexion ici
            die('Erreur : ' . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
