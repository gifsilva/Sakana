<?php
$listaMesas = $listaMesas ?? [];
?>

<div class="mesas-container">

    <div class="mesas-header">

        <div>
            <h1>Pedidos</h1>
            <p>Escolha uma mesa para lançar um novo pedido ou ver o resumo dos pedidos já feitos.</p>
        </div>

    </div>


    <div class="mesas-grid">

        <?php if (empty($listaMesas)): ?>

            <div class="mesas-vazio">
                <p>Nenhuma mesa cadastrada.</p>
            </div>

        <?php else: ?>

            <?php foreach ($listaMesas as $mesa): ?>

                <div class="mesa-card">

                    <img
                        src="<?= app_url('view/images/mesa.png') ?>"
                        alt="Mesa <?= htmlspecialchars($mesa['numeromesa']) ?>"
                        class="mesa-imagem"
                    >

                    <h3>
                        Mesa <?= htmlspecialchars($mesa['numeromesa']) ?>
                    </h3>

                    <p>
                        <?= htmlspecialchars($mesa['lugares'] ?? '0') ?> lugares
                    </p>

                    <?php if ($mesa['status'] === 'Disponivel'): ?>

                        <span class="mesa-status disponivel">
                            Disponível
                        </span>

                    <?php else: ?>

                        <span class="mesa-status indisponivel">
                            Indisponível
                        </span>

                    <?php endif; ?>


                    <div class="mesa-acoes">

                        <?php if ($mesa['status'] === 'Disponivel' && $_SESSION['setorAtual'] == "gerencia" || $_SESSION['setorAtual'] == "atendimento"): ?>

                            <a href="<?= app_url('index.php?action=novoPedido&id=' . $mesa['idmesa']) ?>"
                               class="btn-abrir">
                                Pedido
                            </a>

                        <?php endif; ?>

                        <a href="<?= app_url('index.php?action=verResumoPedido&id=' . $mesa['idmesa']) ?>"
                           class="btn-resumo">
                            Ver resumo
                        </a>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</div>
