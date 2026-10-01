<?php
    class AccountModel{
        public function cadastrarUser($nome, $email, $senha){
            require_once __DIR__ . "/../config/conexao.php";

            try{
                $conexao = Conexao::getConn();

                // verificação de email existente
                $sqlCheck = "SELECT COUNT(*) as count FROM LoginUser WHERE email = :email";
                $stmtCheck = $conexao->prepare($sqlCheck);
                $stmtCheck->bindParam(":email", $email);
                $stmtCheck->execute();
                $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

                if ($result["count"] > 0) {
                    return ["ok" => false, "error" => "email_exists"];
                }
                
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                $sql = "INSERT INTO LoginUser (nomeUser, email, senha) VALUES (:nome, :email, :senha)";
                $stmt = $conexao->prepare($sql);
                $stmt->bindParam(":nome", $nome);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":senha", $senhaHash);
                $stmt->execute();
                return ["ok" => true];
            } catch(PDOException $e){
                if ($e->getCode() == 23000) {
                    return ["ok" => false, "error" => "email_exists"];
                }
                error_log("Erro PDO ao cadastrar usuário: " . $e->getMessage());
                return ["ok" => false, "error" => "database_error"];
            } catch(Throwable $e) {
                error_log("Erro inesperado ao cadastrar usuário: " . $e->getMessage());
                return ["ok" => false, "error" => "database_error"];
            }
        }

        public function logarUser($email, $senha){
            require_once __DIR__ . "/../config/conexao.php";

            try{
                $conexao = Conexao::getConn();

                $sql = "SELECT u.idUser,
                               u.nomeUser,
                               u.email,
                               u.senha,
                               u.fotoPerfil,
                               u.nivelAcesso,
                               f.idFuncionario,
                               f.nomeFunc,
                               c.nomeCargo
                        FROM LoginUser u
                        LEFT JOIN Funcionario f ON u.idUser = f.idUser
                        LEFT JOIN cargo c ON f.idCargo = c.idCargo
                        WHERE u.email = :email LIMIT 1";
                $stmt = $conexao->prepare($sql);
                $stmt->bindParam(":email", $email);
                $stmt->execute();

                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$user || !password_verify($senha, $user["senha"])){
                    return ["ok" => false, "error" => "invalid_credentials"];
                }

                return ["ok" => true, "user" => $user];

            } catch(PDOException $e){
                error_log("Erro ao fazer login: " . $e->getMessage());
                return ["ok" => false, "error" => "database_error"];
            } catch(Throwable $e) {
                error_log("Erro inesperado ao logar usuário: " . $e->getMessage());
                return ["ok" => false, "error" => "database_error"];
            }
        }
    }
?>
