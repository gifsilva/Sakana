<?php
class Conexao {
    public static function getConn() {
        $host = "localhost";
        $port = "";
        $dbname = "bdSakana";
        $user = "root";
        $pass = "";

        try {
            $conexao = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $pass);
            $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conexao;
        } catch (PDOException $e) {
            throw new RuntimeException("db_unavailable", 0, $e);
        }
    }
    
}
