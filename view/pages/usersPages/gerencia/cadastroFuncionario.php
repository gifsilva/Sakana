<link rel="stylesheet" href="view/css/cardapio.css?v=3">
<script src="<?= app_url('view/js/inputMasks.js') ?>" defer></script>

<h2 class="titulo-form-func">Cadastrar funcionários na equipe</h2>

<form action="<?= app_url('index.php?action=cadastrarFunc') ?>" method="POST" class="form-grupo">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

    <div class="cardapio-field cardapio-field-wide">
        <label class="form-label" for="nomeFunc">Nome completo do funcionário</label>
        <input type="text" id="nomeFunc" name="nomeFunc" class="form-input" required
               autocomplete="name" placeholder="Ex: Ana Paula Silva">
    </div>

    <div class="cardapio-field">
        <label class="form-label" for="cpf">CPF</label>
        <input type="text" id="cpf" name="cpf" class="form-input" required
               inputmode="numeric" autocomplete="off"
               placeholder="000.000.000-00" minlength="14" maxlength="14"
               pattern="\d{3}\.\d{3}\.\d{3}-\d{2}"
               title="Informe o CPF no formato 000.000.000-00">
    </div>

    <div class="cardapio-field">
        <label class="form-label" for="cargo">Cargo</label>
        <select id="cargo" name="cargo" class="form-input" required>
            <option value="">Selecione o cargo</option>
            <option value="Garçom">Garçom</option>
            <option value="Cozinha">Cozinha</option>
        </select>
    </div>

    <div class="cardapio-field">
        <label class="form-label" for="email">Email do funcionário</label>
        <input type="email" id="email" name="email" class="form-input" required
               autocomplete="off" placeholder="Ex: ana.silva@sakana.com">
    </div>

    <div class="cardapio-field">
        <label class="form-label" for="senha">Senha do funcionário</label>
        <input type="password" id="senha" name="senha" class="form-input" required
               minlength="8" autocomplete="new-password" placeholder="Mínimo de 8 caracteres">
    </div>

    <div class="cardapio-field cardapio-field-wide">
        <label class="form-label" for="endereco">Endereço</label>
        <input type="text" id="endereco" name="endereco" class="form-input" required
               autocomplete="street-address" placeholder="Ex: Avenida Brasil, nº 100 - CEP: 00000-000">
    </div>

    <button type="submit" class="btn-primary">Cadastrar colaborador</button>
</form>
