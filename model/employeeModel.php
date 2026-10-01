<?php
    require_once __DIR__ . "/../config/conexao.php";
    class EmployeeModel {
        public function cadastrarFunc(
            $nome,
            $cpf,
            $endereco,
            $cargo,
            $email,
            $senha
        ) {
            $conexao = null;

        try {
            $conexao = Conexao::getConn();

            $nivelAcesso = match ($cargo) {
                "Garçom" => "garcom",
                "Cozinha" => "cozinha",
                default => null
            };

            if ($nivelAcesso === null) {
                return [
                    "ok" => false,
                    "error" => "invalid_cargo"
                ];
            }

            $sqlCpf = "SELECT COUNT(*)
                    FROM Funcionario
                    WHERE cpf = :cpf";

            $stmtCpf = $conexao->prepare($sqlCpf);
            $stmtCpf->execute([
                ":cpf" => $cpf
            ]);

            if ((int) $stmtCpf->fetchColumn() > 0) {
                return [
                    "ok" => false,
                    "error" => "cpf_exists"
                ];
            }

            $sqlEmail = "SELECT COUNT(*)
                        FROM LoginUser
                        WHERE email = :email";

            $stmtEmail = $conexao->prepare($sqlEmail);
            $stmtEmail->execute([
                ":email" => $email
            ]);

            if ((int) $stmtEmail->fetchColumn() > 0) {
                return [
                    "ok" => false,
                    "error" => "email_exists"
                ];
            }

            $sqlCargo = "SELECT idCargo
                        FROM cargo
                        WHERE nomeCargo = :cargo
                        LIMIT 1";

            $stmtCargo = $conexao->prepare($sqlCargo);
            $stmtCargo->execute([
                ":cargo" => $cargo
            ]);

            $idCargo = $stmtCargo->fetchColumn();

            if (!$idCargo) {
                return [
                    "ok" => false,
                    "error" => "cargo_not_found"
                ];
            }

            $conexao->beginTransaction();

            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $sqlUser = "INSERT INTO LoginUser
                        (nomeUser, email, senha, nivelAcesso)
                        VALUES (:nome, :email, :senha, :nivelAcesso)";

            $stmtUser = $conexao->prepare($sqlUser);
            $stmtUser->execute([
                ":nome" => $nome,
                ":email" => $email,
                ":senha" => $senhaHash,
                ":nivelAcesso" => $nivelAcesso
            ]);

            $idUser = $conexao->lastInsertId();

            $sqlFuncionario = "INSERT INTO Funcionario
                            (nomeFunc, cpf, endereco, idUser, idCargo)
                            VALUES (:nome, :cpf, :endereco, :idUser, :idCargo)";

            $stmtFuncionario = $conexao->prepare($sqlFuncionario);
            $stmtFuncionario->execute([
                ":nome" => $nome,
                ":cpf" => $cpf,
                ":endereco" => $endereco,
                ":idUser" => $idUser,
                ":idCargo" => $idCargo
            ]);

            $conexao->commit();

            return [
                "ok" => true
            ];
        } catch (PDOException $e) {
            if ($conexao !== null && $conexao->inTransaction()) {
                $conexao->rollBack();
            }

            if ($e->getCode() === "23000") {
                return [
                    "ok" => false,
                    "error" => "duplicate_data"
                ];
            }

            error_log(
                "Erro PDO ao cadastrar funcionário: " . $e->getMessage()
            );

            return [
                "ok" => false,
                "error" => "database_error"
            ];
        } catch (Throwable $e) {
            if ($conexao !== null && $conexao->inTransaction()) {
                $conexao->rollBack();
            }

            error_log(
                "Erro inesperado ao cadastrar funcionário: " . $e->getMessage()
            );

            return [
                "ok" => false,
                "error" => "database_error"
            ];
        }
    }

        public function listarTodosFuncionario() {
            $sql = "SELECT f.idFuncionario,
                   f.nomeFunc,
                   f.cpf,
                   f.endereco,
                   c.nomeCargo AS cargo
            FROM Funcionario f
            LEFT JOIN cargo c ON f.idCargo = c.idCargo
            ORDER BY f.idFuncionario ASC";

            $stmt = Conexao::getConn()->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>
