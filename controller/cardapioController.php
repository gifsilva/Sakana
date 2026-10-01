<?php
require_once __DIR__ . "/baseController.php";
require_once __DIR__ . "/../model/categoriaModel.php";
class CardapioController extends BaseController
{
    public function cadastrarCategoria()
    {
        $this->requirePost("logadoGerencia&page=cadastroCategoria");
        $this->startSession();
        $this->validateCsrfOrRedirect("logadoGerencia&page=cadastroCategoria");
        $this->requireSetor("gerencia");

        if (!isset($_FILES['fotoCategoria']) || $_FILES['fotoCategoria']['error'] !== 0) {
            echo "<script>alert('Nenhum arquivo foi enviado.'); window.history.back();</script>";
            exit;
        }

        $extensao = strtolower(pathinfo($_FILES['fotoCategoria']['name'], PATHINFO_EXTENSION));
        $extensoesPermitidas = ['jpg', 'jpeg', 'png'];

        if (!in_array($extensao, $extensoesPermitidas, true)) {
            echo "<script>alert('Formato inválido. Envie apenas JPG, JPEG ou PNG.'); window.history.back();</script>";
            exit;
        }

        $nomeFoto = uniqid('', true) . '.' . $extensao;
        $diretorioUpload = __DIR__ . '/../view/images/categorias';
        $arquivoDestino = $diretorioUpload . '/' . $nomeFoto;

        if (!is_dir($diretorioUpload)) {
            echo "<script>alert('A pasta view/images/categorias não existe.'); window.history.back();</script>";
            exit;
        }

        if (!move_uploaded_file($_FILES['fotoCategoria']['tmp_name'], $arquivoDestino)) {
            echo "<script>alert('Erro ao mover o arquivo para o servidor.'); window.history.back();</script>";
            exit;
        }

        $caminhoWeb = app_url('view/images/categorias/' . $nomeFoto);

        $nomeCategoria = $_POST["nomeCategoria"] ?? "";
        $descCategoria = $_POST["descCategoria"] ?? "";
        $fotoCategoria = $caminhoWeb;

        if ($nomeCategoria === "" || $descCategoria === "" || $fotoCategoria === null) {
            $this->flashAndRedirect("warning", "Preencha todos os campos para continuar.", "logadoGerencia&page=cadastroCategoria");
        }

        require_once __DIR__ . "/../model/categoriaModel.php";
        $categoriaModel = new CategoriaModel();
        $resultado = $categoriaModel->cadastrarCategoria($nomeCategoria, $descCategoria, $fotoCategoria);

        if ($resultado["ok"]) {
            // Renova token após sucesso para reduzir reutilização.
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
            $this->flashAndRedirect("success", "Categoria cadastrada com sucesso!", "logadoGerencia&page=cadastroCategoria");
        }

        $error = $resultado["error"] ?? "unknown_error";

        if ($error === "name_exists") {
            $msg = "Já existe uma categoria com esse nome.";
        } elseif ($error === "database_error") {
            $msg = "Banco de dados indisponível. Tente mais tarde.";
        } else {
            $msg = "Erro ao Cadastrar. Tente novamente.";
        }

        $this->flashAndRedirect("error", $msg, "logadoGerencia&page=cadastroCategoria");
    }

    public function listarCategorias()
    {
        $this->requireAnySetor([
            "gerencia",
            "atendimento"
        ]);
        $categoriaModel = new CategoriaModel();
        $listaCategorias = $categoriaModel->listarCategorias();

        require_once __DIR__ . "/../view/pages/usersPages/gerencia/cardapio.php";
    }

    public function cadastrarProduto()
    {
        $this->requirePost("logadoGerencia&page=cadastroProduto");
        $this->startSession();
        $this->validateCsrfOrRedirect("logadoGerencia&page=cadastroProduto");
        $this->requireSetor("gerencia");

        if (!isset($_FILES['fotoProduto']) || $_FILES['fotoProduto']['error'] !== 0) {
            echo "<script>alert('Nenhum arquivo foi enviado.'); window.history.back();</script>";
            exit;
        }

        $extensao = strtolower(pathinfo($_FILES['fotoProduto']['name'], PATHINFO_EXTENSION));
        $extensoesPermitidas = ['jpg', 'jpeg', 'png'];

        if (!in_array($extensao, $extensoesPermitidas, true)) {
            echo "<script>alert('Formato inválido. Envie apenas JPG, JPEG ou PNG.'); window.history.back();</script>";
            exit;
        }

        $nomeFoto = uniqid('', true) . '.' . $extensao;
        $diretorioUpload = __DIR__ . '/../view/images/produtos';
        $arquivoDestino = $diretorioUpload . '/' . $nomeFoto;

        if (!is_dir($diretorioUpload)) {
            echo "<script>alert('A pasta view/images/produtos não existe.'); window.history.back();</script>";
            exit;
        }

        if (!move_uploaded_file($_FILES['fotoProduto']['tmp_name'], $arquivoDestino)) {
            echo "<script>alert('Erro ao mover o arquivo para o servidor.'); window.history.back();</script>";
            exit;
        }

        $caminhoWeb = app_url('view/images/produtos/' . $nomeFoto);

        $nomeProduto = $_POST["nomeProduto"] ?? "";
        $descProduto = $_POST["descProduto"] ?? "";
        $categoriaId = $_POST["idCategoria"] ?? "";
        $valorProduto = trim($_POST["valorProduto"] ?? "");
        $valorProduto = str_replace('.', '', $valorProduto);
        $valorProduto = str_replace(',', '.', $valorProduto);
        $fotoProduto = $caminhoWeb;

        if ($nomeProduto === "" || $descProduto === "" || $fotoProduto === null || $categoriaId === "" || $valorProduto === "") {
            $this->flashAndRedirect("warning", "Preencha todos os campos para continuar.", "logadoGerencia&page=cadastroProduto");
        }

        require_once __DIR__ . "/../model/produtoModel.php";
        $produtoModel = new ProdutoModel();
        $resultado = $produtoModel->cadastrarProduto($nomeProduto, $descProduto, $fotoProduto, $categoriaId, $valorProduto);

        if ($resultado["ok"]) {
            // Renova token após sucesso para reduzir reutilização.
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
            $this->flashAndRedirect("success", "Produto cadastrado com sucesso!", "logadoGerencia&page=cadastroProduto");
        }

        $error = $resultado["error"] ?? "unknown_error";

        if ($error === "name_exists") {
            $msg = "Já existe um produto com esse nome.";
        } elseif ($error === "database_error") {
            $msg = "Banco de dados indisponível. Tente mais tarde.";
        } else {
            $msg = "Erro ao Cadastrar. Tente novamente.";
        }

        $this->flashAndRedirect("error", $msg, "logadoGerencia&page=cadastroProduto");
    }

    public function listarProdutos()
    {
        $this->requireAnySetor([
            "gerencia",
            "atendimento"
        ]);
        $produtoModel = new ProdutoModel();
        $listaProdutos = $produtoModel->listarProdutos();

        require_once __DIR__ . "/../view/pages/usersPages/gerencia/cardapio.php";
    }

    public function seedCardapio()
    {
        $this->requirePost("logadoGerencia&page=cadastroCategoria");
        $this->startSession();
        $this->validateCsrfOrRedirect("logadoGerencia&page=cadastroCategoria");
        $this->requireSetor("gerencia", "logadoGerencia&page=cardapio");

        require_once __DIR__ . "/../model/categoriaModel.php";
        require_once __DIR__ . "/../model/produtoModel.php";

        $categoriaModel = new CategoriaModel();
        $produtoModel = new ProdutoModel();

        $categoriasExemplo = [
            ["Bebidas", "Sucos, refrigerantes e drinks", app_url("view/images/seed/categorias/bebidas.jpg")],
            ["Sushis", "Combinados e peças avulsas", app_url("view/images/seed/categorias/sushis.jpg")],
            ["Temakis", "Enrolado em forma de cone recheado", app_url("view/images/seed/categorias/temakis.jpg")],
        ];

        $idsCategorias = [];
        foreach ($categoriasExemplo as [$nome, $desc, $foto]) {
            $resultado = $categoriaModel->cadastrarCategoria($nome, $desc, $foto);
            if ($resultado["ok"]) {
                $idsCategorias[$nome] = $resultado["id"];
            }
        }

        $produtosExemplo = [
            ["Coca-Cola 350ml", "Lata gelada de Coca-Cola", app_url("view/images/seed/produtos/cocacola.jpg"), "Bebidas", 6.00],
            ["Guaraná Antarctica 350ml", "Lata gelada de Guarana", app_url("view/images/seed/produtos/guarana.jpg"), "Bebidas", 6.00],
            ["Combinado 20 peças", "Sushi e sashimi variados", app_url("view/images/seed/produtos/combinado20.jpg"), "Sushis", 45.00],
            ["Combinado 10 peças", "Sushis variados", app_url("view/images/seed/produtos/combinado10.jpg"), "Sushis", 25.00],
            ["Temaki Salmão Cru", "Temaki cru", app_url("view/images/seed/produtos/temakiCru.jpg"), "Temakis", 15.00],
            ["Temaki Salmão Grelhado", "Temaki grelhado", app_url("view/images/seed/produtos/temakiGrelhado.jpg"), "Temakis", 15.00],
        ];

        foreach ($produtosExemplo as [$nome, $desc, $foto, $nomeCategoria, $valor]) {
            $categoriaId = $idsCategorias[$nomeCategoria] ?? null;
            if ($categoriaId) {
                $produtoModel->cadastrarProduto($nome, $desc, $foto, $categoriaId, $valor);
            }
        }

        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        $this->flashAndRedirect("success", "Cardápio de exemplo cadastrado!", "logadoGerencia&page=cardapio");
    }

    public function excluirExemplosCardapio(): void
    {
        $this->requirePost("logadoGerencia&page=cardapio");
        $this->startSession();
        $this->validateCsrfOrRedirect("logadoGerencia&page=cardapio");
        $this->requireSetor("gerencia", "logadoGerencia&page=cardapio");

        $nomesProdutos = [
            "Coca-Cola 350ml",
            "Guaraná Antarctica 350ml",
            "Combinado 20 peças",
            "Combinado 10 peças",
            "Temaki Salmão Cru",
            "Temaki Salmão Grelhado"
        ];
        $nomesCategorias = ["Bebidas", "Sushis", "Temakis"];

        try {
            $conexao = Conexao::getConn();
            $conexao->beginTransaction();

            $placeholdersProdutos = implode(", ", array_fill(0, count($nomesProdutos), "?"));
            $stmtProdutos = $conexao->prepare(
                "DELETE FROM produto WHERE nomeProduto IN ($placeholdersProdutos)"
            );
            $stmtProdutos->execute($nomesProdutos);

            $stmtCategoria = $conexao->prepare(
                "SELECT idCategoria FROM categoria WHERE nomeCategoria = ?"
            );
            $stmtProdutosCategoria = $conexao->prepare(
                "SELECT COUNT(*) FROM produto WHERE idCategoria = ?"
            );
            $stmtExcluirCategoria = $conexao->prepare(
                "DELETE FROM categoria WHERE idCategoria = ?"
            );

            foreach ($nomesCategorias as $nomeCategoria) {
                $stmtCategoria->execute([$nomeCategoria]);
                $idCategoria = $stmtCategoria->fetchColumn();

                if ($idCategoria === false) {
                    continue;
                }

                $stmtProdutosCategoria->execute([$idCategoria]);
                if ((int) $stmtProdutosCategoria->fetchColumn() === 0) {
                    $stmtExcluirCategoria->execute([$idCategoria]);
                }
            }

            $conexao->commit();
            $this->flashAndRedirect("success", "Exemplos do cardápio excluídos!", "logadoGerencia&page=cardapio");
        } catch (PDOException $e) {
            if (isset($conexao) && $conexao->inTransaction()) {
                $conexao->rollBack();
            }

            $this->flashAndRedirect("error", "Não foi possível excluir os exemplos.", "logadoGerencia&page=cardapio");
        }
    }

    public function excluirCategoria()
    {
        $this->requirePost("logadoGerencia&page=cardapio");
        $this->startSession();
        $this->validateCsrfOrRedirect("logadoGerencia&page=cardapio");
        $this->requireSetor("gerencia", "logadoGerencia&page=cardapio");

        $idCategoria = $_POST["idCategoria"] ?? "";

        if ($idCategoria === "") {
            $this->flashAndRedirect("warning", "Categoria inválida.", "logadoGerencia&page=cardapio");
        }

        require_once __DIR__ . "/../model/categoriaModel.php";
        $categoriaModel = new CategoriaModel();
        $resultado = $categoriaModel->excluirCategoria($idCategoria);

        if ($resultado["ok"]) {
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
            $this->flashAndRedirect("success", "Categoria excluída com sucesso!", "logadoGerencia&page=cardapio");
        }

        $error = $resultado["error"] ?? "unknown_error";

        if ($error === "has_products") {
            $msg = "Não é possível excluir: existem produtos cadastrados nessa categoria.";
        } elseif ($error === "database_error") {
            $msg = "Banco de dados indisponível. Tente mais tarde.";
        } else {
            $msg = "Erro ao excluir categoria.";
        }

        $this->flashAndRedirect("error", $msg, "logadoGerencia&page=cardapio");
    }

    public function excluirProduto()
    {
        $this->requirePost("logadoGerencia&page=cardapio");
        $this->startSession();
        $this->validateCsrfOrRedirect("logadoGerencia&page=cardapio");
        $this->requireSetor("gerencia", "logadoGerencia&page=cardapio");

        $idProduto = $_POST["idProduto"] ?? "";

        if ($idProduto === "") {
            $this->flashAndRedirect("warning", "Produto inválido.", "logadoGerencia&page=cardapio");
        }

        require_once __DIR__ . "/../model/produtoModel.php";
        $produtoModel = new ProdutoModel();
        $resultado = $produtoModel->excluirProduto($idProduto);

        if ($resultado["ok"]) {
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
            $this->flashAndRedirect("success", "Produto excluído com sucesso!", "logadoGerencia&page=cardapio");
        }

        $error = $resultado["error"] ?? "unknown_error";

        if ($error === "database_error") {
            $msg = "Banco de dados indisponível. Tente mais tarde.";
        } else {
            $msg = "Erro ao excluir produto.";
        }

        $this->flashAndRedirect("error", $msg, "logadoGerencia&page=cardapio");
    }
}
