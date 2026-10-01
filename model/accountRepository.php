<?php
class AccountRepository
{
    private $conexao;

    public function __construct()
    {
        require_once __DIR__ . "/../config/conexao.php";
        $this->conexao = Conexao::getConn();
    }

    public function emailExists(string $email): bool
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM LoginUser WHERE email = :email";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result["count"] > 0;
        } catch (PDOException $e) {
            error_log("Erro ao verificar email: " . $e->getMessage());
            throw new RuntimeException("database_error");
        }
    }

    public function create(string $nome, string $email, string $senhaHash): bool
    {
        try {
            $sql = "INSERT INTO LoginUser (nomeUser, email, senha) VALUES (:nome, :email, :senha)";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":senha", $senhaHash, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new RuntimeException("email_exists");
            }
            error_log("Erro ao criar usuário: " . $e->getMessage());
            throw new RuntimeException("database_error");
        }
    }

    public function findByEmail(string $email): ?array
    {
        try {
            $sql = "SELECT idUser, nomeUser, email, senha, fotoPerfil FROM LoginUser WHERE email = :email LIMIT 1";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            return $usuario ?: null;
        } catch (PDOException $e) {
            error_log("Erro ao buscar usuário: " . $e->getMessage());
            throw new RuntimeException("database_error");
        }
    }

    public function findPasswordHashById(int $idUser): ?string
    {
        try {
            $sql = "SELECT senha FROM LoginUser WHERE idUser = :idUser LIMIT 1";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(":idUser", $idUser, PDO::PARAM_INT);
            $stmt->execute();

            $hash = $stmt->fetchColumn();

            return $hash === false ? null : $hash;
        } catch (PDOException $e) {
            error_log("Erro ao buscar senha: " . $e->getMessage());
            throw new RuntimeException("database_error");
        }
    }

    public function updatePasswordById(int $idUser, string $senhaHash): bool
    {
        try {
            $sql = "UPDATE LoginUser SET senha = :senha WHERE idUser = :idUser";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(":senha", $senhaHash, PDO::PARAM_STR);
            $stmt->bindParam(":idUser", $idUser, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao atualizar senha: " . $e->getMessage());
            throw new RuntimeException("database_error");
        }
    }

    public function updateProfile(int $idUser, string $nome, string $email, ?string $fotoPerfil = null): bool
    {
        try {
            $sql = "UPDATE LoginUser SET nomeUser = :nome, email = :email, fotoPerfil = :fotoPerfil WHERE idUser = :idUser";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":idUser", $idUser, PDO::PARAM_INT);

            if ($fotoPerfil === null) {
                $stmt->bindValue(":fotoPerfil", null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(":fotoPerfil", $fotoPerfil, PDO::PARAM_STR);
            }

            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                throw new RuntimeException("email_exists");
            }

            error_log("Erro ao atualizar perfil: " . $e->getMessage());
            throw new RuntimeException("database_error");
        }
    }
}
