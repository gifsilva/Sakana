<?php
$setor = $_GET["setor"] ?? "gerencia";

$titulos = [
    "gerencia" => "Login da Gerência",
    "atendimento" => "Login do Atendimento",
    "cozinha" => "Login da Cozinha"
];

$titulo = $titulos[$setor] ?? "Login do setor";
?>
<link rel="stylesheet" href="view/css/style.css">
<body class="page">
    <?php
        SessionHelper::garanteSessaoIniciada();
        $flash = SessionHelper::getFlash();

        if ($flash && !in_array($flash["type"], ["error", "warning", "info", "success"])) {
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
            <h2><?= htmlspecialchars($titulo, ENT_QUOTES, "UTF-8") ?></h2>

            <form action="<?= app_url('index.php?action=entrarSetor') ?>" method="post" class="input-group">
                <input type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8") ?>">

                <input type="hidden"
                    name="setor"
                    value="<?= htmlspecialchars($setor, ENT_QUOTES, "UTF-8") ?>">

                <input type="email" name="email" placeholder="Email" required>

                <input type="password" name="senha" placeholder="Senha" required>

                <button type="submit" class="btn-primary">
                    Entrar
                </button>
            </form>
        </div>
    </div>
</body>
<script src="<?= app_url('view/js/alerts.js') ?>" defer></script>