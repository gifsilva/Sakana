<?php

require_once __DIR__ . "/baseController.php";

class UsersController extends BaseController
{
    private function renderPainel(string $pagina = "home", array $dados = []): void
    {
        SessionHelper::gerarToken();

        $mapaPaginas = [
            "home" => null,

            "editarPerfil" => __DIR__ . "/../view/pages/usersPages/edtPerfil/edtPerfil.php",

            "funcionarios" => __DIR__ . "/../view/pages/usersPages/gerencia/funcionarios.php",

            "cadastroFuncionario" => __DIR__ . "/../view/pages/usersPages/gerencia/cadastroFuncionario.php",

            "consultaFuncionario" => __DIR__ . "/../view/pages/usersPages/gerencia/consultaFuncionario.php",

            "pedidos" => __DIR__ . "/../view/pages/usersPages/gerencia/pedidos.php",

            "novoPedido" => __DIR__ . "/../view/pages/usersPages/gerencia/novoPedido.php",

            "resumoPedido" => __DIR__ . "/../view/pages/usersPages/gerencia/resumoPedido.php",

            "cardapio" => __DIR__ . "/../view/pages/usersPages/gerencia/cardapio.php",

            "cadastroProduto" => __DIR__ . "/../view/pages/usersPages/gerencia/cadastroProduto.php",

            "cadastroCategoria" => __DIR__ . "/../view/pages/usersPages/gerencia/cadastroCategoria.php",

            "consultaCardapio" => __DIR__ . "/../view/pages/usersPages/gerencia/cardapio.php",

            "consultarCardapio" => __DIR__ . "/../view/pages/usersPages/gerencia/cardapio.php",

            "mesas" => __DIR__ . "/../view/pages/usersPages/gerencia/mesas.php",

            "cadastroMesa" => __DIR__ . "/../view/pages/usersPages/gerencia/mesasForm.php",

            "editarMesa" => __DIR__ . "/../view/pages/usersPages/gerencia/mesasForm.php"
        ];

        if (!array_key_exists($pagina, $mapaPaginas)) {
            $pagina = "home";
        }

        $paginaAtiva = $pagina;
        $arquivoConteudo = $mapaPaginas[$pagina];

        extract($dados, EXTR_SKIP);

        require_once __DIR__ . "/../view/pages/usersPages/gerencia/ManagementPanel.php";
    }


    public function editarPerfil(): void
    {
        $this->requireAuth("login");
        $this->startSession();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            SessionHelper::garanteSessaoIniciada();

            if (!SessionHelper::validarToken()) {

                SessionHelper::setFlash(
                    "error",
                    "Tentativa de requisição inválida."
                );

                $this->renderPainel("editarPerfil", []);

                return;
            }

            $nome = trim($_POST["nome"] ?? "");
            $email = trim($_POST["email"] ?? "");

            if ($nome === "" || $email === "") {

                SessionHelper::setFlash(
                    "warning",
                    "Preencha nome e email para continuar."
                );

                $this->renderPainel("editarPerfil", []);

                return;
            }

            require_once __DIR__ . "/../model/accountRepository.php";

            try {

                $accountRepository = new AccountRepository();

                $emailAtual = $_SESSION["emailUser"] ?? "";

                $usuarioAtual =
                    $emailAtual !== ""
                    ? $accountRepository->findByEmail($emailAtual)
                    : null;

                if ($usuarioAtual === null) {

                    SessionHelper::setFlash(
                        "error",
                        "Não foi possível localizar o usuário atual."
                    );

                    $this->renderPainel("editarPerfil", []);

                    return;
                }

                if (
                    $email !== $usuarioAtual["email"] &&
                    $accountRepository->emailExists($email)
                ) {

                    SessionHelper::setFlash(
                        "error",
                        "Este email já está sendo usado por outro usuário."
                    );

                    $this->renderPainel("editarPerfil", []);

                    return;
                }

                $fotoPerfil = $usuarioAtual["fotoPerfil"] ?? null;

                if (
                    !empty($_FILES["fotoPerfil"]["tmp_name"]) &&
                    is_uploaded_file($_FILES["fotoPerfil"]["tmp_name"])
                ) {

                    $extensao = strtolower(
                        pathinfo(
                            $_FILES["fotoPerfil"]["name"],
                            PATHINFO_EXTENSION
                        )
                    );

                    $permitidas = [
                        "jpg",
                        "jpeg",
                        "png",
                        "webp"
                    ];

                    if (!in_array($extensao, $permitidas, true)) {

                        SessionHelper::setFlash(
                            "warning",
                            "A imagem deve ser JPG, JPEG, PNG ou WEBP."
                        );

                        $this->renderPainel("editarPerfil", []);

                        return;
                    }

                    if ($_FILES["fotoPerfil"]["size"] > 2 * 1024 * 1024) {

                        SessionHelper::setFlash(
                            "warning",
                            "A imagem deve ter no máximo 2MB."
                        );

                        $this->renderPainel("editarPerfil", []);

                        return;
                    }

                    $dirDestino =
                        __DIR__ . "/../view/images/perfis";

                    if (
                        !is_dir($dirDestino) &&
                        !mkdir($dirDestino, 0755, true) &&
                        !is_dir($dirDestino)
                    ) {

                        SessionHelper::setFlash(
                            "error",
                            "Não foi possível preparar a pasta de imagens."
                        );

                        $this->renderPainel("editarPerfil", []);

                        return;
                    }

                    $nomeArquivo =
                        "perfil_" .
                        (int) $usuarioAtual["idUser"] .
                        "_" .
                        bin2hex(random_bytes(8)) .
                        "." .
                        $extensao;

                    $caminhoDestino =
                        $dirDestino . "/" . $nomeArquivo;

                    if (
                        !move_uploaded_file(
                            $_FILES["fotoPerfil"]["tmp_name"],
                            $caminhoDestino
                        )
                    ) {

                        SessionHelper::setFlash(
                            "error",
                            "Não foi possível salvar a imagem de perfil."
                        );

                        $this->renderPainel("editarPerfil", []);

                        return;
                    }

                    $fotoPerfil = app_url("view/images/perfis/" . $nomeArquivo);
                }

                $ok = $accountRepository->updateProfile(
                    (int) $usuarioAtual["idUser"],
                    $nome,
                    $email,
                    $fotoPerfil
                );

                if ($ok) {

                    $_SESSION["nomeUser"] = $nome;
                    $_SESSION["emailUser"] = $email;
                    $_SESSION["fotoPerfil"] = $fotoPerfil ?? "";

                    SessionHelper::setFlash(
                        "success",
                        "Perfil atualizado com sucesso."
                    );
                } else {

                    SessionHelper::setFlash(
                        "error",
                        "Não foi possível atualizar o perfil."
                    );
                }
            } catch (RuntimeException $e) {

                SessionHelper::setFlash(
                    "error",
                    "Erro ao atualizar o perfil. Tente novamente."
                );
            }

            $this->renderPainel("editarPerfil", []);

            return;
        }

        SessionHelper::gerarToken();

        $this->renderPainel("editarPerfil", []);
    }

    public function alterarSenha(): void
    {
        $voltar = "editarPerfil";

        $this->requirePost($voltar);
        $this->startSession();
        $this->validateCsrfOrRedirect($voltar);
        $this->requireAuth("login");

        $senhaAtual     = $_POST["senhaAtual"] ?? "";
        $novaSenha      = $_POST["novaSenha"] ?? "";
        $confirmarSenha = $_POST["confirmarSenha"] ?? "";

        // Campos enviados como array (name="novaSenha[]") não são senhas válidas.
        if (!is_string($senhaAtual) || !is_string($novaSenha) || !is_string($confirmarSenha)) {
            $this->flashAndRedirect("warning", "Dados inválidos.", $voltar);
        }

        if ($senhaAtual === "" || $novaSenha === "" || $confirmarSenha === "") {
            $this->flashAndRedirect("warning", "Preencha todos os campos de senha.", $voltar);
        }

        if (strlen($novaSenha) < 8) {
            $this->flashAndRedirect("warning", "A nova senha deve ter pelo menos 8 caracteres.", $voltar);
        }

        if (strlen($novaSenha) > 72) {
            $this->flashAndRedirect("warning", "A nova senha deve ter no máximo 72 caracteres.", $voltar);
        }

        if ($novaSenha !== $confirmarSenha) {
            $this->flashAndRedirect("warning", "A confirmação não confere com a nova senha.", $voltar);
        }

        if ($novaSenha === $senhaAtual) {
            $this->flashAndRedirect("warning", "A nova senha deve ser diferente da atual.", $voltar);
        }

        require_once __DIR__ . "/../model/accountRepository.php";

        try {
            $accountRepository = new AccountRepository();
            $idUser = (int) $_SESSION["idUser"];

            $hashAtual = $accountRepository->findPasswordHashById($idUser);

            if ($hashAtual === null || !password_verify($senhaAtual, $hashAtual)) {
                $this->flashAndRedirect("error", "Senha atual incorreta.", $voltar);
            }

            $novoHash = password_hash($novaSenha, PASSWORD_DEFAULT);

            if (!$accountRepository->updatePasswordById($idUser, $novoHash)) {
                $this->flashAndRedirect("error", "Não foi possível alterar a senha.", $voltar);
            }
        } catch (RuntimeException $e) {
            $this->flashAndRedirect("error", "Erro ao alterar a senha. Tente novamente mais tarde.", $voltar);
        }

        // Credencial mudou: renova o id da sessão e o token CSRF.
        session_regenerate_id(true);
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));

        $this->flashAndRedirect("success", "Senha alterada com sucesso!", $voltar);
    }


    public function logarGerencia(): void
    {
        $this->requireSetor("gerencia");

        $this->renderPainel("home", []);
    }

    // ACESSO ÀS PÁGINAS DE GERÊNCIA / ATENDIMENTO
    public function logadoGerencia(string $pagina = "home", array $dados = []): void
    {
        $this->requireAnySetor(["gerencia", "atendimento", "cozinha"]);

        $todos    = ["gerencia", "atendimento", "cozinha"];
        $salao    = ["gerencia", "atendimento"];
        $gerencia = ["gerencia"];

        $permissoes = [
            "home"                => $todos,
            "pedidos"             => $todos,
            "novoPedido"          => $todos,
            "resumoPedido"        => $todos,

            "cardapio"            => $salao,
            "consultaCardapio"    => $salao,
            "consultarCardapio"   => $salao,
            "mesas"               => $salao,

            "funcionarios"        => $gerencia,
            "consultaFuncionario" => $gerencia,
            "cadastroFuncionario" => $gerencia,
            "cadastroProduto"     => $gerencia,
            "cadastroCategoria"   => $gerencia,
            "cadastroMesa"        => $gerencia,
            "editarMesa"          => $gerencia,
        ];

        if (!array_key_exists($pagina, $permissoes)) {
            $pagina = "home";
        }

        $this->requireAnySetor($permissoes[$pagina], "logadoGerencia");

        $paginasQuePrecisamDeMesa = [
            "novoPedido"   => "logadoGerencia&page=pedidos",
            "resumoPedido" => "logadoGerencia&page=pedidos",
            "editarMesa"   => "logadoGerencia&page=mesas",
        ];

        if (isset($paginasQuePrecisamDeMesa[$pagina]) && empty($dados["mesa"])) {
            $this->flashAndRedirect(
                "warning",
                "Selecione uma mesa primeiro.",
                $paginasQuePrecisamDeMesa[$pagina]
            );
        }

        if ($pagina === "consultaFuncionario") {
            require_once __DIR__ . "/../model/employeeModel.php";
            $employeeModel = new EmployeeModel();
            $dados["listaFuncionarios"] = $employeeModel->listarTodosFuncionario();
        }

        if (in_array($pagina, ["cardapio", "consultaCardapio", "consultarCardapio"], true)) {
            require_once __DIR__ . "/../model/categoriaModel.php";
            require_once __DIR__ . "/../model/produtoModel.php";

            $dados["listaCategorias"] = (new CategoriaModel())->listarCategorias();
            $dados["listaProdutos"]   = (new ProdutoModel())->listarProdutos();
        }

        if ($pagina === "cadastroProduto") {
            require_once __DIR__ . "/../model/categoriaModel.php";
            $dados["listaCategorias"] = (new CategoriaModel())->listarCategorias();
        }

        if ($pagina === "mesas" || $pagina === "pedidos") {
            require_once __DIR__ . "/../model/mesaModel.php";
            $dados["listaMesas"] = (new Mesa())->listarMesas();
        }

        $this->renderPainel($pagina, $dados);
    }
}
