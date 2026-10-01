<?php $ehGerencia = ($_SESSION["setorAtual"] ?? null) === "gerencia"; ?>
<script src="<?= app_url('view/js/searchProducts.js?v=2') ?>" defer></script>
<link rel="stylesheet" href="view/css/cardapio.css?v=5">

<div class="cardapio-container">

    <h2 class="titulo-pagina">Cardápio</h2>
    <?php if ($ehGerencia): ?>
        <details class="acoes-cardapio">
            <summary class="acoes-cardapio-toggle">
                <span class="acoes-cardapio-icon">☰</span>
                <span>Ações do cardápio</span>
                <span class="acoes-cardapio-chevron"></span>
            </summary>
            <div class="card-mod">
                <a href="<?= app_url('index.php?action=logadoGerencia&page=cadastroProduto') ?>" class="card-opcao">
                    <div class="card-icon">🍣</div>
                    <h3>Cadastrar produtos</h3>
                </a>
                <a href="<?= app_url('index.php?action=logadoGerencia&page=cadastroCategoria') ?>" class="card-opcao">
                    <div class="card-icon">🍱</div>
                    <h3>Cadastrar categorias</h3>
                </a>
                <a href="<?= app_url('index.php?action=logadoGerencia&page=consultaCardapio') ?>" class="card-opcao">
                    <div class="card-icon">📋</div>
                    <h3>Visualizar cardápio</h3>
                </a>
                <form action="<?= app_url('index.php?action=seedCardapio') ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <button type="submit" class="card-opcao">
                        <div class="card-icon">✨</div>
                        <h3>Cadastrar Exemplo</h3>
                    </button>
                </form>
                <form action="<?= app_url('index.php?action=excluirExemplosCardapio') ?>" method="POST"
                    onsubmit="return confirm('Deseja excluir os exemplos do cardápio?');">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <button type="submit" class="card-opcao card-opcao-danger">
                        <div class="card-icon">🗑️</div>
                        <h3>Excluir Exemplos</h3>
                    </button>
                </form>
            </div>
        </details>
    <?php endif; ?>
    <div class="cardapio-conteudo">
        <div class="cardapio-header">
            <div class="cardapio-categoria">
                <?php if (isset($listaCategorias) && count($listaCategorias) > 0): ?>
                    <button class="aba-categoria aba-ativa" data-categoria="todos" onclick="filtrarCategoria(this.dataset.categoria)">
                        <p class="categoria-nome">Todas as categorias</p>
                    </button>
                    <?php foreach ($listaCategorias as $c): ?>
                        <button class="aba-categoria" data-categoria="<?php echo htmlspecialchars($c['nomeCategoria'], ENT_QUOTES, 'UTF-8'); ?>" onclick="filtrarCategoria(this.dataset.categoria)">
                            <img class="imagem-categoria" src="<?php echo htmlspecialchars(app_asset_url($c['imgCategoria']), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($c['nomeCategoria'], ENT_QUOTES, 'UTF-8'); ?>">
                            <p class="categoria-nome"><?php echo htmlspecialchars($c['nomeCategoria'], ENT_QUOTES, 'UTF-8'); ?></p>
                        </button>
                        <?php if ($ehGerencia): ?>
                            <form action="<?= app_url('index.php?action=excluirCategoria') ?>" method="POST"
                                onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?');">
                                <input type="hidden" name="idCategoria" value="<?php echo htmlspecialchars($c['idCategoria'], ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                <button type="submit" class="btn-excluir">🗑️</button>
                            </form>
                        <?php endif; ?>
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
            <?php if (isset($listaProdutos) && count($listaProdutos) > 0): ?>
                <?php foreach ($listaProdutos as $p): ?>
                    <div class="produto-frame" data-categoria="<?php echo htmlspecialchars($p['nomeCategoria'], ENT_QUOTES, 'UTF-8'); ?>">
                        <img src="<?php echo htmlspecialchars(app_asset_url($p['imgProduto']), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($p['nomeProduto'], ENT_QUOTES, 'UTF-8'); ?>" class="imagem-produto">
                        <div class="produto-info">
                            <h3 class="produto-nome"><?php echo htmlspecialchars($p['nomeProduto'], ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p class="produto-descricao"><?php echo htmlspecialchars($p['descProduto'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="produto-valor">R$ <?php echo number_format($p['valorProduto'], 2, ',', '.'); ?></p>
                        </div>
                        <?php if ($ehGerencia): ?>
                            <form action="<?= app_url('index.php?action=excluirProduto') ?>" method="POST"
                                onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                                <input type="hidden" name="idProduto" value="<?php echo htmlspecialchars($p['idProduto'], ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                <button type="submit" class="btn-excluir">🗑️ Excluir</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="cardapio-vazio-produtos">Nenhum produto cadastrado.</div>
            <?php endif; ?>
        </div>
    </div>

</div>