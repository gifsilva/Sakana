<?php
$setorAtual = $_SESSION["setorAtual"] ?? null;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php
    $titulosSetor = [
        "gerencia" => "Gerência",
        "atendimento" => "Atendimento",
        "cozinha" => "Cozinha"
    ];
    $tituloSetor = $titulosSetor[$setorAtual] ?? "Sistema";
    ?>
    <title><?= htmlspecialchars($tituloSetor, ENT_QUOTES, "UTF-8") ?> | Sakana</title>

    <link rel="stylesheet" href="view/css/style.css?v=4">
    <link rel="stylesheet" href="view/css/gerencia.css?v=3">
    <link rel="stylesheet" href="view/css/perfil.css?v=1">
    <link rel="stylesheet" href="view/css/alerts.css">
</head>

<body class="page-gerencia">

    <header class="topbar">
        <div class="logo-area">
            <img src="<?= app_url('view/images/logo.png') ?>" alt="Logo Sakana" class="logo">
            <span class="logo-text">SAKANA</span>
        </div>

        <div class="titulo-sistema">
            SIMULADOR DE RESTAURANTE
        </div>

        <div class="user-area">
            <span><?php if (isset($_SESSION['nomeUser'])): ?>
                    <?= htmlspecialchars($_SESSION['nomeUser'], ENT_QUOTES, 'UTF-8') ?>
                <?php else: ?>
                    Usuário não logado
                <?php endif; ?>
            </span>
            <a href="<?= app_url('index.php?action=editarPerfil') ?>" class="user-profile-link">
                <img src="<?= htmlspecialchars(app_asset_url(($_SESSION['fotoPerfil'] ?? '') ?: app_url('view/images/user.png')), ENT_QUOTES, 'UTF-8') ?>" alt="Usuário" class="user-icon">
            </a>

        </div>
    </header>

    <div class="layout">
        <aside class="sidebar">
            <?php if ($setorAtual === "gerencia"): ?>
                <a href="<?= app_url('index.php?action=logadoGerencia&page=funcionarios') ?>"
                    class="menu-btn">
                    Funcionários
                </a>
            <?php endif; ?>

            <?php if (
                $setorAtual === "gerencia" ||
                $setorAtual === "atendimento" ||
                $setorAtual === "cozinha"
            ): ?>
                <a href="<?= app_url('index.php?action=logadoGerencia&page=pedidos') ?>"
                    class="menu-btn">
                    Pedidos
                </a>
            <?php endif; ?>

            <?php if (
                $setorAtual === "gerencia" ||
                $setorAtual === "atendimento"
            ): ?>
                <a href="<?= app_url('index.php?action=logadoGerencia&page=cardapio') ?>"
                    class="menu-btn">
                    Cardápio
                </a>

                <a href="<?= app_url('index.php?action=logadoGerencia&page=mesas') ?>"
                    class="menu-btn">
                    Mesas
                </a>
            <?php endif; ?>

            <a href="<?= app_url('index.php?action=painelAcesso') ?>"
                class="btn-setor">
                Trocar de setor
            </a>
        </aside>

        <main class="conteudo">
            <?php $flash = SessionHelper::getFlash(); ?>
            <?php if ($flash): ?>
                <div class="alert alert-toast alert-<?php echo htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8'); ?>" role="alert" aria-live="polite">
                    <span class="alert-text"><?php echo htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <button type="button" class="alert-close" aria-label="Fechar aviso">×</button>
                </div>
            <?php endif; ?>
            <?php if (!empty($arquivoConteudo) && file_exists($arquivoConteudo)): ?>
                <?php require $arquivoConteudo; ?>
            <?php else: ?>
                <div class="home-gerencia"></div>
            <?php endif; ?>
        </main>

    </div>

</body>

</html>