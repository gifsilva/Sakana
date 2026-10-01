let FuncionarioAtivo = 'todos';

function buscar() {
    const input = document.getElementById('pesquisaFuncionario').value.toLowerCase();
    const cards = document.querySelectorAll('.tabela-linha');

    cards.forEach(linha => {
        const nome = linha.querySelector('.celula-nome')?.innerText.toLowerCase() || "";
        const cpf = linha.querySelectorAll('td')[2]?.innerText.toLowerCase() || "";

        const cargoLinha = linha.getAttribute("data-cargo");

        const bateBusca = nome.includes(input) || cpf.includes(input);
        const bateCategoria = (FuncionarioAtivo === "todos" || cargoLinha === FuncionarioAtivo);

        linha.style.display = (bateBusca && bateCategoria) ? "table-row" : "none";
    });
}

function filtrarCategoria(cargo) {
    FuncionarioAtivo = cargo;

    document.querySelectorAll(".aba-cargo").forEach(btn => {
        btn.classList.remove("aba-ativa");
        if (btn.getAttribute("onclick").includes(`'${cargo}'`)) {
            btn.classList.add('aba-ativa');
        }
    });
    buscar();
}