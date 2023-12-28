<?php

require('config.php');

define("SERVERNAME", $servername);
define("DBNAME", $dbname);
define("USERNAME", $username);
define("PASSWORD", $password);
class Database
{
    private $connection = null;
    public function __construct($dbhost = "", $dbname = "", $username = "", $password = "")
    {
        try {
            $this->connection = new PDO("mysql:host={$dbhost};dbname={$dbname};charset=utf8mb4;", $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    private function executeStatement($statement = "", $parameters = [])
    {
        try {
            $stmt = $this->connection->prepare($statement);
            $stmt->execute($parameters);
            return $stmt;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function Insert($statement = "", $parameters = [])
    {
        try {
            error_log("Выполняется SQL: $statement, Параметры: " . print_r($parameters, true));
            $this->executeStatement($statement, $parameters);
            return $this->connection->lastInsertId();
        } catch (Exception $e) {
            throw new Exception($e->getMessage() . " SQL: $statement, Параметры: " . print_r($parameters, true));
        }
    }

    public function Select($statement = "", $parameters = [])
    {
        try {
            $stmt = $this->executeStatement($statement, $parameters);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    function SelectSingle($query, $params = []) {
        global $db;
    
        $result = $db->Select($query, $params);
    
        if ($result && count($result) > 0) {
            return reset($result);
        }
    
        return null;
    }

    public function Update($statement = "", $parameters = [])
    {
        try {
            $this->executeStatement($statement, $parameters);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function Remove($statement = "", $parameters = [])
    {
        try {
            $this->executeStatement($statement, $parameters);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}

$db = new Database(
    SERVERNAME,      
    DBNAME, 
    USERNAME,           
    PASSWORD                
);