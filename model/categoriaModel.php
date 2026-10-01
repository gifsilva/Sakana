<?php
    require_once __DIR__ . "/../config/conexao.php";
    class CategoriaModel {
        public function cadastrarCategoria($nome, $descricao, $foto) {

            try {
                $conexao = Conexao::getConn();

                // verificação nome existente
                $sqlCheck = "SELECT COUNT(*) AS count FROM categoria WHERE nomeCategoria = :nome";
                $stmtCheck = $conexao->prepare($sqlCheck);
                $stmtCheck->bindParam(":nome", $nome);
                $stmtCheck->execute();
                $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

                if (($result["count"] ?? 0) > 0) {
                    return ["ok" => false, "error" => "name_exists"];
                }

                $sql = "INSERT INTO categoria (nomeCategoria, descCategoria, imgCategoria) VALUES (:nome, :descricao, :foto)";
                $stmt = $conexao->prepare($sql);
                $stmt->bindParam(":nome", $nome);
                $stmt->bindParam(":descricao", $descricao);
                $stmt->bindParam(":foto", $foto);
                $stmt->execute();

    
                return ["ok" => true, "id" => $conexao->lastInsertId()];
            } catch(PDOException $e){
                error_log("Erro PDO ao cadastrar categoria: " . $e->getMessage());
                return ["ok" => false, "error" => "database_error"];
            } catch(Throwable $e) {
                error_log("Erro inesperado ao cadastrar categoria: " . $e->getMessage());
                return ["ok" => false, "error" => "database_error"];
            }
        }

        public function listarCategorias() {
            $sql = "SELECT * FROM categoria ORDER BY idCategoria ASC";
            $stmt = Conexao::getConn()->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    

    public function excluirCategoria($id) {
        try {
            $conexao = Conexao::getConn();

            $sqlCheck = "SELECT COUNT(*) AS count FROM produto WHERE idCategoria = :id";
            $stmtCheck = $conexao->prepare($sqlCheck);
            $stmtCheck->bindParam(":id", $id);
            $stmtCheck->execute();
            $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if (($result["count"] ?? 0) > 0) {
                return ["ok" => false, "error" => "has_products"];
            }

            $sql = "DELETE FROM categoria WHERE idCategoria = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            return ["ok" => true];
        } catch(PDOException $e) {
            error_log("Erro PDO ao excluir categoria: " . $e->getMessage());
            return ["ok" => false, "error" => "database_error"];
        }
    }
    }
?>
