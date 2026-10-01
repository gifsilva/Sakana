<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAKANA | Simulador</title>
    <link rel="stylesheet" href="view/css/style.css">
</head>
<body class="page-home">

    <main class="home-container" id="home-container">
        <section class="home-brand" aria-label="Sakana">
            <img src="view/images/logo.png" alt="Logo Sakana" id="logo">
            <h1 class="titulo">SAKANA</h1>
            <h2 class="subtitulo">SIMULADOR GERENCIAL</h2>

            <div class="home-actions">
                <a class="btn-primary" href="index.php?action=login">
                    Iniciar Simulador
                </a>
                <button class="btn-secondary" type="button" id="about-toggle" aria-expanded="false" aria-controls="about-panel">
                    Quem Somos
                </button>
            </div>
        </section>

        <section class="about-panel" id="about-panel" aria-labelledby="about-title" hidden>
            <h2 id="about-title">Quem Somos</h2>
            <p>Somos alunos do <strong>
                3º ano do curso Técnico em Informática da ETEC de Franco da Rocha</strong> 
                e desenvolvemos este projeto como parte da nossa formação acadêmica.</p>
            <p>Este site é um <strong>simulador de um sistema de gerenciamento</strong>, criado com o objetivo de colocar em prática os conhecimentos adquiridos durante o curso, especialmente nas áreas de 
            <strong>programação, banco de dados, desenvolvimento web e engenharia de software</strong>.</p>
            <p>Por meio do simulador, buscamos representar de forma prática o funcionamento de uma plataforma de gerenciamento, proporcionando uma experiência próxima à utilização de um sistema real.</p>
        </section>
    </main>

    <script>
        const aboutToggle = document.getElementById('about-toggle');
        const aboutPanel = document.getElementById('about-panel');
        const homeContainer = document.getElementById('home-container');
        let closeTimer;
        let closeTransitionHandler;

        aboutToggle.addEventListener('click', () => {
            const isOpen = aboutToggle.getAttribute('aria-expanded') === 'true';

            clearTimeout(closeTimer);
            if (closeTransitionHandler) {
                aboutPanel.removeEventListener('transitionend', closeTransitionHandler);
                closeTransitionHandler = null;
            }

            aboutToggle.setAttribute('aria-expanded', String(!isOpen));
            aboutToggle.textContent = isOpen ? 'Quem Somos' : 'Fechar';

            const finishClosing = () => {
                if (aboutToggle.getAttribute('aria-expanded') === 'false') {
                    aboutPanel.hidden = true;
                    aboutPanel.style.maxHeight = '';
                }
            };

            closeTransitionHandler = (event) => {
                if (event.target !== aboutPanel || event.propertyName !== 'max-height') {
                    return;
                }

                if (aboutToggle.getAttribute('aria-expanded') === 'true') {
                    aboutPanel.style.maxHeight = 'none';
                } else {
                    finishClosing();
                }
            };
            aboutPanel.addEventListener('transitionend', closeTransitionHandler);

            if (isOpen) {
                aboutPanel.style.maxHeight = `${aboutPanel.getBoundingClientRect().height}px`;
                aboutPanel.getBoundingClientRect();
                homeContainer.classList.remove('has-about');
                aboutPanel.style.maxHeight = '0px';

                const closeDuration = window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 400 : 650;
                closeTimer = window.setTimeout(finishClosing, closeDuration);
                return;
            }

            aboutPanel.hidden = false;
            aboutPanel.style.maxHeight = '0px';
            aboutPanel.getBoundingClientRect();
            homeContainer.classList.add('has-about');
            aboutPanel.style.maxHeight = `${aboutPanel.scrollHeight}px`;
        });
    </script>
</body>
</html>
