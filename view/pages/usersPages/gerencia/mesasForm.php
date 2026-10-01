<?php

$mesa = $mesa ?? null;

?>

<div class="mesa-form-container">

    <div class="mesa-form-header">
        <h1><?= $mesa ? 'Editar mesa' : 'Cadastrar mesa' ?></h1>

        <p>
            <?= $mesa
                ? 'Altere o número da mesa e a quantidade de lugares.'
                : 'Cadastre uma nova mesa no restaurante.'
            ?>
        </p>
    </div>


    <form
        action="<?= app_url('index.php?action=' . ($mesa ? 'atualizarMesa' : 'salvarMesa')) ?>"
        method="POST"
        class="mesa-form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

        <?php if ($mesa): ?>

            <input
                type="hidden"
                name="idMesa"
                value="<?= htmlspecialchars($mesa['idmesa']) ?>">

        <?php endif; ?>


        <div class="form-group">

            <label for="numeroMesa">
                Número da mesa
            </label>

            <input type="number" id="numeroMesa" name="numeroMesa" min="1" required value="<?= htmlspecialchars($mesa['numeromesa'] ?? '') ?>" placeholder="Ex: 1">

        </div>

        <div class="form-group">

            <label for="numeroLugares">
                Número de lugares
            </label>

            <input type="number" id="numeroLugares" name="numeroLugares" min="1" required value="<?= htmlspecialchars($mesa['lugares'] ?? '') ?>" placeholder="Ex: 4">

        </div>


        <div class="form-info">

            <span>🍽️</span>

            <p>
                Informe a quantidade de lugares disponíveis nesta mesa.
            </p>

        </div>


        <div class="form-acoes">

            <a
                href="<?= app_url('index.php?action=logadoGerencia&page=mesas') ?>"
                class="btn-secondary">Cancelar
            </a>


            <button type="submit" class="btn-primary">
                <?= $mesa ? 'Salvar alterações' : 'Cadastrar mesa' ?>
            </button>

        </div>

    </form>

</div>