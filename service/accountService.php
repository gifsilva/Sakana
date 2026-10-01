<?php
require_once __DIR__ . "/inputValidator.php";
class AccountService {
    private $accountRepository;
    private $validator;

    public function __construct(AccountRepository $accountRepository) {
        $this->accountRepository = $accountRepository;
        $this->validator = new InputValidator();
    }

    private function hashPassword(string $senha): string {
        return password_hash($senha, PASSWORD_DEFAULT);
    }

    public function cadastrar(array $input): array {
        $nome = trim((string)($input["txtNome"]?? ""));
        $email = trim((string)($input["txtEmail"] ?? ""));
        $senha = (string)($input["txtSenha"] ?? "");
        $confirmaSenha = (string)($input["txtConfirmaSenha"] ?? "");

        // validação de entrada
        $this->validator->clear()
            ->notEmpty("nome", $nome, 2)
            ->notEmpty("email", $email)
            ->email("email", $email)
            ->notEmpty("senha", $senha, 8)
            ->notEmpty("confirmaSenha", $confirmaSenha, 8)
            ->match("confirmaSenha", $senha, $confirmaSenha, "Senhas");

        if (!$this->validator->isValid()) {
            return [
                "ok" => false,
                "flashType" => "warning",
                "flashMessage" => $this->validator->getFirstError(),
                "nextAction" => "cadastro"
            ];
        }

        try {
            if ($this->accountRepository->emailExists($email)) {
                return [
                    "ok" => false,
                    "flashType" => "warning",
                    "flashMessage" => "Este email já está cadastrado. use outro ou faça login.",
                    "nextAction" => "cadastro"
                ];
            }

            $senhaHash = $this->hashPassword($senha);

            $this->accountRepository->create($nome, $email, $senhaHash);

            return [
                "ok" => true,
                "flashType" => "success",
                "flashMessage" => "Cadastro realizado com sucesso!",
                "nextAction" => "login"
            ];
        } catch (RuntimeException $e) {
            $error = $e->getMessage();

            if ($error === "email_exists") {
                $msg = "Este email já está cadastrado. Use outro ou faça login.";
            } elseif ($error === "database_error") {
                $msg = "Banco de dados indisponível. Tente mais tarde.";
            } else {
                $msg = "Erro ao cadastrar. Tente novamente.";
            }

            return [
                "ok" => false,
                "flashType" => "error",
                "flashMessage" => $msg,
                "nextAction" => "cadastro"
            ];
        }
    }

    public function logar(array $input): array {
        $email = trim((string)($input["txtEmail"] ?? ""));
        $senha = (string)($input["txtSenha"] ?? "");

        $this->validator->clear()
            ->notEmpty("email", $email)
            ->email("email", $email)
            ->notEmpty("senha", $senha, 8);

        if (!$this->validator->isValid()) {
            return [
                "ok" => false,
                "flashType" => "warning",
                "flashMessage" => $this->validator->getFirstError(),
                "nextAction" => "login"
            ];
        }

        try {
            $usuario = $this->accountRepository->findByEmail($email);

            if (!$usuario || !password_verify($senha, $usuario["senha"])) {
                return [
                    "ok" => false,
                    "flashType" => "error",
                    "flashMessage" => "Email ou senha incorretos. Tente novamente.",
                    "nextAction" => "login"
                ];
            }

            unset($usuario["senha"]);

            return [
                "ok" => true,
                "user" => $usuario
            ];
        } catch (RuntimeException $e) {
            return [
                "ok" => false,
                "flashType" => "error",
                "flashMessage" => "Banco de dados indisponível. Tente mais tarde.",
                "nextAction" => "login"
            ];
        }
    }
}
?>