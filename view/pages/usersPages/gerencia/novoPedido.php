<?php
$mesa = $mesa ?? null;
$listaProdutos = $listaProdutos ?? [];
$listaCategorias = $listaCategorias ?? [];
?>

<script src="<?= app_url('view/js/searchProducts.js?v=2') ?>" defer></script>
<link rel="stylesheet" href="view/css/cardapio.css?v=5">

<div class="cardapio-container">

    <div class="mesas-header">
        <div>
            <h1>Pedido - Mesa <?= htmlspecialchars($mesa['numeromesa'] ?? '') ?></h1>
            <p>Escolha os produtos e as quantidades para esta mesa.</p>
        </div>

        <a href="<?= app_url('index.php?action=logadoGerencia&page=pedidos') ?>" class="btn-secondary">
            Voltar
        </a>
    </div>

    <form action="<?= app_url('index.php?action=salvarPedido') ?>" method="POST" class="pedido-form">

        <input type="hidden" name="idMesa" value="<?= htmlspecialchars($mesa['idmesa'] ?? '') ?>">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <div class="cardapio-conteudo">
            <div class="cardapio-header">
                <div class="cardapio-categoria">
                    <?php if (count($listaCategorias) > 0): ?>
                        <button type="button" class="aba-categoria aba-ativa" data-categoria="todos" onclick="filtrarCategoria(this.dataset.categoria)">
                            <p class="categoria-nome">Todas as categorias</p>
                        </button>
                        <?php foreach ($listaCategorias as $c): ?>
                            <button type="button" class="aba-categoria" data-categoria="<?= htmlspecialchars($c['nomeCategoria'], ENT_QUOTES, 'UTF-8') ?>" onclick="filtrarCategoria(this.dataset.categoria)">
                                <img class="imagem-categoria" src="<?= htmlspecialchars(app_asset_url($c['imgCategoria']), ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($c['nomeCategoria'], ENT_QUOTES, 'UTF-8') ?>">
                                <p class="categoria-nome"><?= htmlspecialchars($c['nomeCategoria'], ENT_QUOTES, 'UTF-8') ?></p>
                            </button>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="cardapio-vazio-categoria">Nenhuma categoria registrada.</div>
                    <?php endif; ?>
                </div>
                <div class="cardapio-pesquisa">
                    <input type="text" id="pesquisa-produtos" placeholder="Pesquisar produtos..." onkeyup="buscar()">
                </div>
            </div>

            <div class="cardapio-body">
                <?php if (count($listaProdutos) > 0): ?>
                    <?php foreach ($listaProdutos as $p): ?>
                        <div class="produto-frame" data-categoria="<?= htmlspecialchars($p['nomeCategoria'], ENT_QUOTES, 'UTF-8') ?>">
                            <img src="<?= htmlspecialchars(app_asset_url($p['imgProduto']), ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($p['nomeProduto'], ENT_QUOTES, 'UTF-8') ?>" class="imagem-produto">
                            <div class="produto-info">
                                <h3 class="produto-nome"><?= htmlspecialchars($p['nomeProduto'], ENT_QUOTES, 'UTF-8') ?></h3>
                                <p class="produto-descricao"><?= htmlspecialchars($p['descProduto'], ENT_QUOTES, 'UTF-8') ?></p>
                                <p class="produto-valor">R$ <?= number_format($p['valorProduto'], 2, ',', '.') ?></p>
                            </div>

                            <div class="produto-quantidade">
                                <label for="qtd-<?= (int) $p['idProduto'] ?>">Quantidade</label>
                                <input
                                    type="number"
                                    id="qtd-<?= (int) $p['idProduto'] ?>"
                                    name="quantidade[<?= (int) $p['idProduto'] ?>]"
                                    min="0"
                                    value="0"
                                >
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="cardapio-vazio-produtos">Nenhum produto cadastrado.</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-acoes">
            <a href="<?= app_url('index.php?action=logadoGerencia&page=pedidos') ?>" class="btn-secondary">
                Cancelar
            </a>

            <button type="submit" class="btn-primary">
                Confirmar pedido
            </button>
        </div>

    </form>

</div>
