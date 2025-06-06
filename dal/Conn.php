<?php
namespace App\Dal;

use \PDO;
use \PDOException;

abstract class Conn
{
    private static ?PDO $conn = null;
    private static string $host = "localhost";
    private static string $dbName = "aula";
    private static string $usuario = "root";
    private static string $senha = "Rafaestu0.";

    public static function getConn(): PDO
    {
        if (self::$conn === null) {
            try {
                self::$conn = new PDO(
                    "mysql:host=" . self::$host . ";
                    dbname=" . self::$dbName,
                    self::$usuario,
                    self::$senha
                );
            } catch (\PDOException $e) {
                throw new \PDOException("Erro ao conectar ao banco " . $e->getMessage());
            }
        }

        return self::$conn;
    }


}