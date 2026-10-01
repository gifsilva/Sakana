<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso | Sakana</title>
    <link rel="stylesheet" href="view/css/style.css">
</head>
<body class="page">

    <div class="container">
        <h2 style="color: var(--dark-blue); margin-bottom: 20px; letter-spacing: 2px;">
            ACESSO - Bem-vindo
        </h2>
        
        <div class="card">
            
            <div class="input-group">
                <a href="<?= app_url('index.php?action=loginSetor&setor=gerencia') ?>" 
                   style="text-decoration: none; width: 100%;">
                    <button class="btn-primary" style="width: 100%; margin-bottom: 15px;">
                        GERÊNCIA
                    </button>
                </a>

                <a href="<?= app_url('index.php?action=loginSetor&setor=atendimento') ?>" 
                   style="text-decoration: none; width: 100%;">
                    <button class="btn-primary" style="width: 100%; margin-bottom: 15px;">
                        ATENDIMENTO
                    </button>
                </a>
                
                <a href="<?= app_url('index.php?action=loginSetor&setor=cozinha') ?>" 
                   style="text-decoration: none; width: 100%;">
                    <button class="btn-primary" style="width: 100%;">
                        COZINHA
                    </button>
                </a>

                <a href="<?= app_url('index.php?action=logout') ?>" class="link-voltar" style="margin-top: 20px;">Sair</a>
                
            </div>
        </div>
       
        
    </div>

</body>
</html>
