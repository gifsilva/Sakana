<?php
$flash = SessionHelper::getFlash();
$nomeUsuario = htmlspecialchars($_SESSION["nomeUser"] ?? "", ENT_QUOTES, "UTF-8");
$emailUsuario = htmlspecialchars($_SESSION["emailUser"] ?? "", ENT_QUOTES, "UTF-8");
$avatarAtual = htmlspecialchars(app_asset_url(($_SESSION["fotoPerfil"] ?? "") ?: app_url("view/images/user.png")), ENT_QUOTES, "UTF-8");
?>

<section class="perfil-page">
    <div class="perfil-header-card">
        <div>
            <p class="perfil-kicker">Configurações da conta</p>
            <h1 class="perfil-title">Editar perfil</h1>
            <p class="perfil-description">
                Atualize seu nome, e-mail e foto de perfil para manter o acesso organizado no sistema.
            </p>
        </div>

        <div class="perfil-avatar-card">
            <img src="<?= $avatarAtual ?>" alt="Avatar do usuário" class="perfil-avatar">
            <div>
                <strong><?= $nomeUsuario !== "" ? $nomeUsuario : "Usuário" ?></strong>
                <span>Perfil ativo</span>
            </div>
        </div>
    </div>

    <?php if ($flash !== null): ?>
        <div class="alert alert-<?= htmlspecialchars($flash["type"], ENT_QUOTES, "UTF-8") ?>">
            <?= htmlspecialchars($flash["message"], ENT_QUOTES, "UTF-8") ?>
        </div>
    <?php endif; ?>

    <div class="perfil-layout">
        <?php require_once __DIR__ . "/../../../perfilPage.php"; ?>
    </div>
</section>
