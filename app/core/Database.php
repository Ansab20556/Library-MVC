<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private string $host = '127.0.0.1';
    private string $db   = 'library_db';
    private string $user = 'root';
    private string $pass = '';
    private string $charset = 'utf8mb4';

    private ?PDO $pdo = null;

    public function pdo() {
        try{
            if ($this->pdo === null) {
                $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,   
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         
                    PDO::ATTR_EMULATE_PREPARES   => false,                    
                ];
                echo"succes";
                $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
            }
            return $this->pdo;
        }catch(PDOException $e){
            echo"Error" . $e->getMessage();
        }

    }
}
