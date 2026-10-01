<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Sakana</title>

    <link rel="stylesheet" href="view/css/style.css">
</head>

<body class="page">

    <?php
    SessionHelper::garanteSessaoIniciada();
    $flash = SessionHelper::getFlash();

    if ($flash && !in_array($flash["type"], ["error", "warning", "info"])) {
        $flash = null;
    }
    ?>

    <?php if ($flash): ?>
        <div class="alert alert-toast alert-<?= htmlspecialchars($flash["type"], ENT_QUOTES, "UTF-8") ?>" role="alert" aria-live="polite">
            <span class="alert-text"><?= htmlspecialchars($flash["message"], ENT_QUOTES, "UTF-8") ?></span>
            <button type="button" class="alert-close" aria-label="Fechar aviso">×</button>
        </div>
    <?php endif; ?>

    <div class="container" style="flex-direction:row; gap:120px;">
        <div class="card">
            <h2 style="color: var(--dark-blue);">LOGAR</h2>

            <form class="input-group" action="<?= app_url('index.php?action=logado') ?>" method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8") ?>">

                <input type="email" name="txtEmail" placeholder="Email" maxlength="50" required>
                <input type="password" name="txtSenha" placeholder="Senha" minlength="8" maxlength="16" autocomplete="new-password" required>

                <button type="submit" class="btn-primary">LOGAR</button>

            </form>

            <a href="<?= app_url('index.php?action=cadastro') ?>" class="btn-primary" style="width: 100%; background-color: var(--dark-blue);">
                CADASTRAR
            </a>

        </div>
    </div>

    <script src="<?= app_url('view/js/alerts.js') ?>" defer></script>
</body>

</html>