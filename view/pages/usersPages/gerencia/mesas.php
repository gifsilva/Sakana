<?php
$listaMesas = $listaMesas ?? [];
$setorAtual = $_SESSION["setorAtual"] ?? null;
?>

<div class="mesas-container">

    <div class="mesas-header">

        <div>
            <h1>Mesas</h1>
            <p>Gerenciamento das mesas do restaurante</p>
        </div>

        <?php if ($setorAtual === "gerencia"): ?>

            <a href="<?= app_url('index.php?action=cadastrarMesa') ?>"
                class="btn-primary">
                Cadastrar mesa
            </a>

        <?php endif; ?>

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
                        class="mesa-imagem">

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

                        <?php if ($setorAtual === "atendimento"): ?>

                            <?php if ($mesa['status'] === 'Disponivel'): ?>

                                <form action="<?= app_url('index.php?action=abrirMesa') ?>" method="POST" class="form-inline">
                                    <input type="hidden" name="idMesa" value="<?= (int) $mesa['idmesa'] ?>">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit" class="btn-abrir">Abrir mesa</button>
                                </form>

                            <?php else: ?>

                                <form action="<?= app_url('index.php?action=fecharMesa') ?>" method="POST" class="form-inline">
                                    <input type="hidden" name="idMesa" value="<?= (int) $mesa['idmesa'] ?>">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit" class="btn-fechar">Fechar mesa</button>
                                </form>

                            <?php endif; ?>

                        <?php endif; ?>


                        <?php if ($setorAtual === "gerencia"): ?>

                            <a href="<?= app_url('index.php?action=editarMesa&id=' . $mesa['idmesa']) ?>"
                                class="btn-editar">
                                Editar
                            </a>

                            <form action="<?= app_url('index.php?action=excluirMesa') ?>" method="POST" class="form-inline"
                                onsubmit="return confirm('Deseja realmente excluir esta mesa?');">
                                <input type="hidden" name="idMesa" value="<?= (int) $mesa['idmesa'] ?>">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                <button type="submit" class="btn-excluir">Excluir</button>
                            </form>

                        <?php endif; ?>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</div>