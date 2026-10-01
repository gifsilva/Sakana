<?php
    require_once __DIR__ . "/../config/conexao.php";
    class ProdutoModel {
        public function cadastrarProduto($nome, $descricao, $foto, $categoriaId, $valor) {

            try {
                $conexao = Conexao::getConn();

                // verificação nome existente
                $sqlCheck = "SELECT COUNT(*) AS count FROM produto WHERE nomeProduto = :nome";
                $stmtCheck = $conexao->prepare($sqlCheck);
                $stmtCheck->bindParam(":nome", $nome);
                $stmtCheck->execute();
                $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

                if (($result["count"] ?? 0) > 0) {
                    return ["ok" => false, "error" => "name_exists"];
                }

                $sql = "INSERT INTO produto (idCategoria, nomeProduto, descProduto, imgProduto, valorProduto) VALUES (:categoriaId, :nome, :descricao, :foto, :valor)";
                $stmt = $conexao->prepare($sql);
                $stmt->bindParam(":nome", $nome);
                $stmt->bindParam(":descricao", $descricao);
                $stmt->bindParam(":foto", $foto);
                $stmt->bindParam(":categoriaId", $categoriaId);
                $stmt->bindParam(":valor", $valor);
                $stmt->execute();



                return ["ok" => true];
            } catch(PDOException $e){
                error_log("Erro PDO ao cadastrar produto: " . $e->getMessage());
                return ["ok" => false, "error" => "database_error"];
            } catch(Throwable $e) {
                error_log("Erro inesperado ao cadastrar produto: " . $e->getMessage());
                return ["ok" => false, "error" => "database_error"];
            }
        }

        public function listarProdutos() {
            $sql = "SELECT p.idProduto, p.nomeProduto, p.descProduto, p.imgProduto, p.valorProduto, c.nomeCategoria
                FROM produto p
                INNER JOIN categoria c ON p.idCategoria = c.idCategoria
                ORDER BY p.nomeProduto ASC;";
            $stmt = Conexao::getConn()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function excluirProduto($id){
            try {
            $conexao = Conexao::getConn();

            $sql = "DELETE FROM produto WHERE idProduto = :id";
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
