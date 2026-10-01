<form class="perfil-form" method="post" action="<?= app_url('index.php?action=editarPerfil') ?>" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8") ?>">

    <div class="form-grid">
        <label class="form-field">
            <span>Nome completo</span>
            <input type="text" name="nome" class="form-input" value="<?= $nomeUsuario ?? "" ?>" required>
        </label>

        <label class="form-field">
            <span>E-mail</span>
            <input type="email" name="email" class="form-input" value="<?= $emailUsuario ?? "" ?>" required>
        </label>
    </div>

    <div class="form-grid">
        <label class="form-field">
            <span>Foto de perfil</span>
            <input type="file" name="fotoPerfil" class="form-input" accept="image/png, image/jpeg, image/webp, image/jpg">
            <small class="perfil-upload-help">Formatos aceitos: JPG, JPEG, PNG ou WEBP. Tamanho máximo: 2MB.</small>
        </label>
    </div>

        <div class="perfil-actions">
            <a href="<?= app_url('index.php?action=logadoGerencia') ?>" class="btn-secondary">Cancelar</a>
            <button type="submit" class="btn-primary">Salvar alterações</button>
        </div>
</form>

<form class="perfil-form" method="post" action="<?= app_url('index.php?action=alterarSenha') ?>">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

    <h2 class="perfil-title">Alterar senha</h2>

    <div class="form-grid">
        <label class="form-field">
            <span>Senha atual</span>
            <input type="password" name="senhaAtual" class="form-input"
                   autocomplete="current-password" required>
        </label>
    </div>

    <div class="form-grid">
        <label class="form-field">
            <span>Nova senha</span>
            <input type="password" name="novaSenha" class="form-input"
                   autocomplete="new-password" minlength="8" maxlength="72" required>
        </label>

        <label class="form-field">
            <span>Confirmar nova senha</span>
            <input type="password" name="confirmarSenha" class="form-input"
                   autocomplete="new-password" minlength="8" maxlength="72" required>
        </label>
    </div>

    <div class="perfil-actions">
        <button type="submit" class="btn-primary">Alterar senha</button>
    </div>
</form>