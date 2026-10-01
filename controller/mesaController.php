<?php

require_once __DIR__ . '/baseController.php';
require_once __DIR__ . '/../model/mesaModel.php';

class MesaController extends BaseController
{
    private const PAGINA_MESAS = "logadoGerencia&page=mesas";

    private $mesaModel;

    public function __construct()
    {
        $this->mesaModel = new Mesa();
    }


    public function abrirCadastro()
    {
        $this->requireSetor("gerencia", self::PAGINA_MESAS);

        require_once __DIR__ . "/usersController.php";
        $controller = new UsersController();
        $controller->logadoGerencia("cadastroMesa", []);
    }

    public function editarMesa()
    {
        $this->requireSetor("gerencia", self::PAGINA_MESAS);

        $idMesa = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

        if (!$idMesa) {
            $this->flashAndRedirect("error", "Mesa não encontrada.", self::PAGINA_MESAS);
        }

        $mesa = $this->mesaModel->buscarMesa($idMesa);

        if (!$mesa) {
            $this->flashAndRedirect("error", "Mesa não encontrada.", self::PAGINA_MESAS);
        }

        require_once __DIR__ . "/usersController.php";
        $controller = new UsersController();
        $controller->logadoGerencia("editarMesa", ["mesa" => $mesa]);
    }

    public function salvarMesa()
    {
        $this->requirePost(self::PAGINA_MESAS);
        $this->startSession();
        $this->validateCsrfOrRedirect(self::PAGINA_MESAS);
        $this->requireSetor("gerencia", self::PAGINA_MESAS);

        $numeroMesa    = $this->inteiroPositivoPost("numeroMesa");
        $numeroLugares = $this->inteiroPositivoPost("numeroLugares");

        if ($numeroMesa === null || $numeroLugares === null) {
            $this->flashAndRedirect("warning", "Informe o número da mesa e a quantidade de lugares.", "cadastrarMesa");
        }

        if (!$this->mesaModel->cadastrarMesa($numeroMesa, $numeroLugares)) {
            $this->flashAndRedirect("error", "Erro ao cadastrar mesa. Verifique se o número já existe.", "cadastrarMesa");
        }

        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        $this->flashAndRedirect("success", "Mesa cadastrada com sucesso!", self::PAGINA_MESAS);
    }

    public function atualizarMesa()
    {
        $this->requirePost(self::PAGINA_MESAS);
        $this->startSession();
        $this->validateCsrfOrRedirect(self::PAGINA_MESAS);
        $this->requireSetor("gerencia", self::PAGINA_MESAS);

        $idMesa        = $this->inteiroPositivoPost("idMesa");
        $numeroMesa    = $this->inteiroPositivoPost("numeroMesa");
        $numeroLugares = $this->inteiroPositivoPost("numeroLugares");

        if ($idMesa === null || $numeroMesa === null || $numeroLugares === null) {
            $this->flashAndRedirect("warning", "Dados inválidos.", self::PAGINA_MESAS);
        }

        if (!$this->mesaModel->editarMesa($idMesa, $numeroMesa, $numeroLugares)) {
            $this->flashAndRedirect("error", "Erro ao atualizar mesa. Verifique se o número já existe.", "editarMesa&id=" . $idMesa);
        }

        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        $this->flashAndRedirect("success", "Mesa atualizada com sucesso!", self::PAGINA_MESAS);
    }

    public function excluirMesa()
    {
        $this->requirePost(self::PAGINA_MESAS);
        $this->startSession();
        $this->validateCsrfOrRedirect(self::PAGINA_MESAS);
        $this->requireSetor("gerencia", self::PAGINA_MESAS);

        $idMesa = $this->inteiroPositivoPost("idMesa");

        if ($idMesa === null || !$this->mesaModel->excluirMesa($idMesa)) {
            $this->flashAndRedirect("error", "Erro ao excluir mesa.", self::PAGINA_MESAS);
        }

        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        $this->flashAndRedirect("success", "Mesa excluída com sucesso!", self::PAGINA_MESAS);
    }

    public function abrirMesa()
    {
        $this->alterarStatus("abrir");
    }

    public function fecharMesa()
    {
        $this->alterarStatus("fechar");
    }


    private function alterarStatus(string $operacao): void
    {
        $this->requirePost(self::PAGINA_MESAS);
        $this->startSession();
        $this->validateCsrfOrRedirect(self::PAGINA_MESAS);
        $this->requireSetor("atendimento", self::PAGINA_MESAS);

        $idMesa = $this->inteiroPositivoPost("idMesa");

        if ($idMesa === null) {
            $this->flashAndRedirect("error", "Mesa não encontrada.", self::PAGINA_MESAS);
        }

        $ok = $operacao === "abrir"
            ? $this->mesaModel->abrirMesa($idMesa)
            : $this->mesaModel->fecharMesa($idMesa);

        if (!$ok) {
            $this->flashAndRedirect("error", "Não foi possível alterar o status da mesa.", self::PAGINA_MESAS);
        }

        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        $this->flashAndRedirect("success", "Status da mesa atualizado.", self::PAGINA_MESAS);
    }

    private function inteiroPositivoPost(string $campo): ?int
    {
        $valor = filter_input(INPUT_POST, $campo, FILTER_VALIDATE_INT, [
            "options" => ["min_range" => 1]
        ]);

        return ($valor === false || $valor === null) ? null : $valor;
    }
}