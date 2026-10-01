<?php
require_once __DIR__ . "/baseController.php";
class AccountController extends BaseController {

    public function cadastro(){
        $this->startSession();
        SessionHelper::gerarToken();

        require_once __DIR__ . "/../view/registerPage.php";
    }

    public function cadastrar(){
        $this->requirePost("cadastro");
        $this->startSession();
        $this->validateCsrfOrRedirect("cadastro");

        $nome = $_POST["txtNome"] ?? "";
        $email = $_POST["txtEmail"] ?? "";
        $senha = $_POST["txtSenha"] ?? "";
        $confirmaSenha = $_POST["txtConfirmaSenha"] ?? "";


        // validações
        if ($nome === "" || $email === "" || $senha === "" || $confirmaSenha === "") {
            $this->flashAndRedirect("warning", "Preencha todos os campos para continuar.", "cadastro");
        }

        if($senha !== $confirmaSenha){
            $this->flashAndRedirect("warning", "As senhas não conferem. Digite novamente.", "cadastro");
        }

        require_once __DIR__ . "/../model/accountModel.php";
        $accountModel = new AccountModel();
        $resultado = $accountModel->cadastrarUser($nome, $email, $senha);

        if ($resultado["ok"]) {
            // Renova token após sucesso para reduzir reutilização.
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
            $this->flashAndRedirect("success", "Cadastro realizado com sucesso!", "login");
        }

        $error = $resultado["error"] ?? "unknown_error";

        if ($error === "email_exists") {
            $msg = "Este email já está cadastrado. Use outro ou faça login.";
        }
        elseif ($error === "database_error") {
            $msg = "Banco de dados indisponível. Tente mais tarde.";
        }
        else {
            $msg = "Erro ao Cadastrar. Tente novamente.";
        }

        $this->flashAndRedirect("error", $msg, "cadastro");
    }
}
?>
