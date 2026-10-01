<link rel="stylesheet" href="view/css/cardapio.css">

<div class="cardapio-container">
    <h2 class="titulo-pagina">Funcionários</h2>
    <p class="subtitulo-pagina">Gerencie o cadastro da equipe do restaurante</p>

    <div class="acoes-funcionarios">

        <a href="<?= app_url('index.php?action=logadoGerencia&page=cadastroFuncionario') ?>" class="card-opcao card-opcao-desc">
            <div class="card-icon">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M19 8v6M22 11h-6"/>
                </svg>
            </div>
            <div class="card-opcao-texto">
                <h3>Cadastrar funcionários</h3>
                <p>Cadastre os colaboradores do restaurante</p>
            </div>
        </a>

        <a href="<?= app_url('index.php?action=logadoGerencia&page=consultaFuncionario') ?>" class="card-opcao card-opcao-desc">
            <div class="card-icon">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <div class="card-opcao-texto">
                <h3>Visualizar colaboradores</h3>
                <p>Veja todos os funcionários cadastrados</p>
            </div>
        </a>

    </div>
</div>