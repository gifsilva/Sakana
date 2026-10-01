<?php

require_once __DIR__ . '/baseController.php';
require_once __DIR__ . '/../model/mesaModel.php';
require_once __DIR__ . '/../model/pedidoModel.php';
require_once __DIR__ . '/../model/produtoModel.php';
require_once __DIR__ . '/../model/categoriaModel.php';

class PedidoController extends BaseController
{
    private $mesaModel;
    private $pedidoModel;
    private $produtoModel;
    private $categoriaModel;

    public function __construct()
    {
        $this->mesaModel = new Mesa();
        $this->pedidoModel = new PedidoModel();
        $this->produtoModel = new ProdutoModel();
        $this->categoriaModel = new CategoriaModel();
    }

    /**
     * Exibe o formulário de novo pedido para uma mesa disponível.
     */
    public function novoPedido()
    {
        $this->requireAnySetor(["gerencia", "atendimento"]);
        $this->startSession();

        $idMesa = $_GET['id'] ?? '';

        if (empty($idMesa)) {
            $this->flashAndRedirect("error", "Mesa não encontrada.", "logadoGerencia&page=pedidos");
        }

        $mesa = $this->mesaModel->buscarMesa($idMesa);

        if (!$mesa) {
            $this->flashAndRedirect("error", "Mesa não encontrada.", "logadoGerencia&page=pedidos");
        }

        if ($mesa['status'] !== 'Disponivel') {
            $this->flashAndRedirect("warning", "Esta mesa não está disponível para novos pedidos.", "logadoGerencia&page=pedidos");
        }

        require_once __DIR__ . '/usersController.php';
        $controller = new UsersController();
        $controller->logadoGerencia('novoPedido', [
            'mesa' => $mesa,
            'listaProdutos' => $this->produtoModel->listarProdutos(),
            'listaCategorias' => $this->categoriaModel->listarCategorias()
        ]);
    }

    /**
     * Salva os itens escolhidos no formulário de pedido.
     * A mesa permanece disponível, permitindo lançar novos pedidos
     * nela depois — só fica indisponível quando fechada manualmente
     * na tela de resumo (ver fecharMesaPedido()).
     */
    public function salvarPedido()
    {
        $this->requirePost("logadoGerencia&page=pedidos");
        $this->startSession();
        $this->validateCsrfOrRedirect("logadoGerencia&page=pedidos");
        $this->requireAnySetor(["gerencia", "atendimento"]);

        $idMesa = $_POST['idMesa'] ?? '';
        $quantidades = $_POST['quantidade'] ?? [];

        if (empty($idMesa) || !is_array($quantidades)) {
            $this->flashAndRedirect("warning", "Selecione ao menos um produto.", "logadoGerencia&page=pedidos");
        }

        $mesa = $this->mesaModel->buscarMesa($idMesa);

        if (!$mesa) {
            $this->flashAndRedirect("error", "Mesa não encontrada.", "logadoGerencia&page=pedidos");
        }

        if ($mesa['status'] !== 'Disponivel') {
            $this->flashAndRedirect("error", "Esta mesa não está disponível para novos pedidos.", "logadoGerencia&page=pedidos");
        }

        $produtos = $this->produtoModel->listarProdutos();

        $produtosPorId = [];
        foreach ($produtos as $produto) {
            $produtosPorId[$produto['idProduto']] = $produto;
        }

        $itensRegistrados = 0;

        foreach ($quantidades as $idProduto => $quantidade) {

            $quantidade = filter_var($quantidade, FILTER_VALIDATE_INT);

            if ($quantidade === false || $quantidade <= 0) {
                continue;
            }

            if (!isset($produtosPorId[$idProduto])) {
                continue;
            }

            $valorUnitario = (float) $produtosPorId[$idProduto]['valorProduto'];
            $valorTotalItem = round($valorUnitario * $quantidade, 2);

            $ok = $this->pedidoModel->criarPedido($idMesa, $idProduto, $quantidade, $valorTotalItem);

            if ($ok) {
                $itensRegistrados++;
            }
        }

        if ($itensRegistrados === 0) {
            $this->flashAndRedirect("warning", "Selecione ao menos um produto com quantidade válida.", "novoPedido&id=" . urlencode($idMesa));
        }

        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        $this->flashAndRedirect("success", "Pedido registrado com sucesso!", "logadoGerencia&page=pedidos");
    }

    /**
     * Fecha a mesa a partir da tela de resumo, marcando-a como
     * indisponível. É a única ação que muda o status da mesa nesse
     * fluxo — o método abrirMesa() do model faz exatamente isso
     * (SET status = 'Indisponivel'), apesar do nome legado.
     */
    public function fecharMesaPedido()
    {
        $this->requirePost("logadoGerencia&page=pedidos");
        $this->startSession();
        $this->validateCsrfOrRedirect("logadoGerencia&page=pedidos");
        $this->requireAnySetor(["gerencia", "atendimento"]);

        $idMesa = $_POST['idMesa'] ?? '';

        if (empty($idMesa)) {
            $this->flashAndRedirect("error", "Mesa não encontrada.", "logadoGerencia&page=pedidos");
        }

        $mesa = $this->mesaModel->buscarMesa($idMesa);

        if (!$mesa) {
            $this->flashAndRedirect("error", "Mesa não encontrada.", "logadoGerencia&page=pedidos");
        }

        $this->mesaModel->abrirMesa($idMesa);

        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        $this->flashAndRedirect("success", "Mesa fechada com sucesso.", "verResumoPedido&id=" . urlencode($idMesa));
    }

    /**
     * Exibe o resumo dos pedidos já feitos por uma mesa.
     */
    public function verResumo()
    {
        $this->requireAnySetor(["gerencia", "atendimento", "cozinha"]);
        $this->startSession();

        $idMesa = $_GET['id'] ?? '';

        if (empty($idMesa)) {
            $this->flashAndRedirect("error", "Mesa não encontrada.", "logadoGerencia&page=pedidos");
        }

        $mesa = $this->mesaModel->buscarMesa($idMesa);

        if (!$mesa) {
            $this->flashAndRedirect("error", "Mesa não encontrada.", "logadoGerencia&page=pedidos");
        }

        $listaPedidos = $this->pedidoModel->listarPedidosPorMesa($idMesa);

        $totalGeral = 0;
        foreach ($listaPedidos as $pedido) {
            $totalGeral += (float) $pedido['Valor'];
        }

        require_once __DIR__ . '/usersController.php';
        $controller = new UsersController();
        $controller->logadoGerencia('resumoPedido', [
            'mesa' => $mesa,
            'listaPedidos' => $listaPedidos,
            'totalGeral' => $totalGeral
        ]);
    }
}
