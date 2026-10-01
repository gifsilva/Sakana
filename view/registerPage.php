<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="view/css/style.css">
    <title>Cadastro | Sakana</title>
</head>
<body class="page">

    <?php
        SessionHelper::garanteSessaoIniciada();
        $flash = SessionHelper::getFlash();
    ?>
    <?php if ($flash): ?>
        <div class="alert alert-toast alert-<?= htmlspecialchars($flash["type"], ENT_QUOTES, "UTF-8") ?>" role="alert" aria-live="polite">
            <span class="alert-text"><?= htmlspecialchars($flash["message"], ENT_QUOTES, "UTF-8") ?></span>
            <button type="button" class="alert-close" aria-label="Fechar aviso">×</button>
        </div>
    <?php endif; ?>

    <div class="container" style="flex-direction:row; gap:120px;">
        <!-- DIV CADASTRO -->
        <div class="card">

            <h2 style="color: var(--dark-blue);">CADASTRAR</h2>

            <form class="input-group" action="<?= app_url('index.php?action=cadastrar') ?>" method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8") ?>">

                <input type="text" name="txtNome" placeholder="Nome" minlength="2" maxlength="30" required>
                <input type="email" name="txtEmail" placeholder="Email" maxlength="50" required>
                <input type="password" name="txtSenha" placeholder="Senha" minlength="8" maxlength="16" autocomplete="new-password" required>
                <input type="password" name="txtConfirmaSenha" placeholder="Confirmar senha" minlength="8" maxlength="16" autocomplete="new-password" required>

                <button type="submit" name="btnEnviar" class="btn-primary">CADASTRAR</button>

                <a href="<?= app_url('index.php?action=login') ?>" class="btn-link" style="margin-top: 10px; font-size: 0.9em;">
                Voltar para login
                </a>
            </form>
        </div>
    </div>


    <script src="<?= app_url('view/js/alerts.js') ?>" defer></script>
</body>
</html>
