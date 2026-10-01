<?php
require_once __DIR__ . "/baseController.php";
require_once __DIR__ . "/../model/employeeModel.php";
class EmployeeController extends BaseController
{

    public function cadastrar()
    {
        $voltar = "logadoGerencia&page=cadastroFuncionario";

        $this->requirePost($voltar);
        $this->startSession();
        $this->validateCsrfOrRedirect($voltar);
        $this->requireSetor("gerencia", "logadoGerencia");

        $nome = $_POST["nomeFunc"] ?? "";
        $cpf = preg_replace("/[^0-9]/", "", $_POST["cpf"] ?? "");
        $endereco = $_POST["endereco"] ?? "";
        $cargo = $_POST["cargo"] ?? "";
        $email = trim($_POST["email"] ?? "");
        $senha = $_POST["senha"] ?? "";

        // validações
        if (
            $nome === "" ||
            $cpf === "" ||
            $endereco === "" ||
            $cargo === "" ||
            $email === "" ||
            $senha === ""
        ) {
            $this->flashAndRedirect(
                "warning",
                "Preencha todos os campos para continuar.",
                $voltar
            );
        }

        require_once __DIR__ . "/../service/inputValidator.php";
        $validator = new InputValidator();
        $validator->notEmpty("cpf", $cpf)->cpf("cpf", $cpf);
        $validator->email("email", $email);

        if (strlen($senha) < 8) {
            $this->flashAndRedirect(
                "warning",
                "A senha deve ter no mínimo 8 caracteres.",
                $voltar
            );
        }

        if (!$validator->isValid()) {
            $this->flashAndRedirect("warning", $validator->getFirstError(), "logadoGerencia&page=cadastroFuncionario");
        }

        require_once __DIR__ . "/../model/employeeModel.php";
        $employeeModel = new EmployeeModel();
        $resultado = $employeeModel->cadastrarFunc(
            $nome,
            $cpf,
            $endereco,
            $cargo,
            $email,
            $senha
        );

        if ($resultado["ok"]) {
            // Renova token após sucesso para reduzir reutilização.
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
            $this->flashAndRedirect("success", "Cadastro realizado com sucesso!", "logadoGerencia&page=cadastroFuncionario");
        }

        $error = $resultado["error"] ?? "unknown_error";

        if ($error === "cpf_exists") {
            $msg = "Este CPF já está cadastrado.";
        } elseif ($error === "email_exists") {
            $msg = "Este email já está em uso.";
        } elseif ($error === "invalid_cargo") {
            $msg = "Selecione um cargo válido.";
        } elseif ($error === "database_error") {
            $msg = "Banco de dados indisponível. Tente mais tarde.";
        } else {
            $msg = "Erro ao Cadastrar. Tente novamente.";
        }

        $this->flashAndRedirect("error", $msg, "logadoGerencia&page=cadastroFuncionario");
    }

    public function listarFuncionarios()
    {
        $this->requireSetor("gerencia");
        $employeeModel = new EmployeeModel();
        $listaFuncionarios = $employeeModel->listarTodosFuncionario();

        require_once __DIR__ . "/../view/pages/usersPages/gerencia/consultaFuncionario.php";
    }
}
