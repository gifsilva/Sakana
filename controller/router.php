<?php
$action = $_GET["action"] ?? "home";

switch($action) {

    case "home":
    case "index":
        require_once "homeController.php";
        $controller = new HomeController();
        $controller->index();
        break;

    case "login":
        require_once "accessController.php";
        $controller = new AccessController();
        $controller->loginForm();
        break;

    case "cadastrar":
        require_once "accountController.php";
        $controller = new AccountController();
        $controller->cadastrar();
        break;
    
    case "cadastrarFunc":
        require_once "employeeController.php";
        $controller = new EmployeeController();
        $controller->cadastrar();
        break;

    case "cadastro":
        require_once "accountController.php";
        $controller = new AccountController();
        $controller->cadastro();
        break;

    case "cadastrarCategoria":
        require_once "cardapioController.php";
        $controller = new CardapioController();
        $controller->cadastrarCategoria();
        break;

    case "cadastrarProduto":
        require_once "cardapioController.php";
        $controller = new CardapioController();
        $controller->cadastrarProduto();
        break;

    case "seedCardapio":
        require_once "cardapioController.php";
        $controller = new CardapioController();
        $controller->seedCardapio();
        break;

    case "excluirExemplosCardapio":
        require_once "cardapioController.php";
        $controller = new CardapioController();
        $controller->excluirExemplosCardapio();
        break;

    case "excluirCategoria":
        require_once "cardapioController.php";
        $controller = new CardapioController();
        $controller->excluirCategoria();
        break;

    case "excluirProduto":
        require_once "cardapioController.php";
        $controller = new CardapioController();
        $controller->excluirProduto();
        break;


    case "logado":
        require_once "accessController.php";
        $controller = new AccessController();
        $controller->logado();
        break;

    case "logadoGerencia":
        require_once "usersController.php";
        $controller = new UsersController();
        $controller->logadoGerencia($_GET["page"] ?? "home");
        break;

    case "painelAcesso":
        require_once "accessController.php";
        $controller = new AccessController();
        $controller->painelAcesso();
        break;

    case "editarPerfil":
        require_once "usersController.php";
        $controller = new UsersController();
        $controller->editarPerfil();
        break;

    case "logout":
        require_once "accessController.php";
        $controller = new AccessController();
        $controller->logout();
        break;

    case "alterarSenha":
    require_once "usersController.php";
    $controller = new UsersController();
    $controller->alterarSenha();
    break;
    
    case "loginSetor":
        require_once "accessController.php";
        $controller = new AccessController();
        $controller->loginSetorForm($_GET["setor"] ?? "");
    break;

    case "entrarSetor":
        require_once "accessController.php";
        $controller = new AccessController();
        $controller->entrarSetor();
    break;

    // CASES DE MESA
    case "cadastrarMesa":
        require_once "mesaController.php";
        $controller = new MesaController();
        $controller->abrirCadastro();
    break;

    case "salvarMesa":
        require_once "mesaController.php";
        $controller = new MesaController();
        $controller->salvarMesa();
    break;

    case "editarMesa":
        require_once "mesaController.php";
        $controller = new MesaController();
        $controller->editarMesa();
    break;

    case "atualizarMesa":
        require_once "mesaController.php";
        $controller = new MesaController();
        $controller->atualizarMesa();
    break;

    case "excluirMesa":
        require_once "mesaController.php";
        $controller = new MesaController();
        $controller->excluirMesa();
    break;

    case "abrirMesa":
        require_once "mesaController.php";
        $controller = new MesaController();
        $controller->abrirMesa();
    break;


    case "fecharMesa":
        require_once "mesaController.php";
        $controller = new MesaController();
        $controller->fecharMesa();
    break;

    // CASES DE PEDIDO
    case "novoPedido":
        require_once "pedidoController.php";
        $controller = new PedidoController();
        $controller->novoPedido();
    break;

    case "salvarPedido":
        require_once "pedidoController.php";
        $controller = new PedidoController();
        $controller->salvarPedido();
    break;

    case "verResumoPedido":
        require_once "pedidoController.php";
        $controller = new PedidoController();
        $controller->verResumo();
    break;

    case "fecharMesaPedido":
        require_once "pedidoController.php";
        $controller = new PedidoController();
        $controller->fecharMesaPedido();
    break;

    default:
        require_once "homeController.php";
        $controller = new HomeController();
        $controller->index();
        break;
}
?>


