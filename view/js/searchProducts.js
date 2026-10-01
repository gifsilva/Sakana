let CategoriaAtiva = 'todos';

function buscar() {
    const input = document.getElementById('pesquisa-produtos')?.value.toLowerCase() || "";
    const frames = document.querySelectorAll('.produto-frame');

    frames.forEach(frame => {
        const nome = frame.querySelector('.produto-nome')?.innerText.toLowerCase() || "";
        const desc = frame.querySelector('.produto-descricao')?.innerText.toLowerCase() || "";

        const categoriaProduto = frame.getAttribute("data-categoria")?.toLowerCase().trim() || "";
        const categoriaAtiva = CategoriaAtiva.toLowerCase().trim();

        const bateBusca = nome.includes(input) || desc.includes(input);
        const bateCategoria = (categoriaAtiva === "todos" || categoriaProduto === categoriaAtiva);

        frame.style.display = (bateBusca && bateCategoria) ? "" : "none";
    });
}

function filtrarCategoria(categoria) {
    const categoriaAtiva = categoria?.toString().toLowerCase().trim() || "todos";
    CategoriaAtiva = categoriaAtiva;

    document.querySelectorAll(".aba-categoria").forEach(btn => {
        btn.classList.remove("aba-ativa");
        if (btn.dataset.categoria?.toLowerCase().trim() === categoriaAtiva) {
            btn.classList.add('aba-ativa');
        }
    });
    buscar();
}

// Listener para atualizar busca enquanto digita
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('pesquisa-produtos');
    if (input) {
        input.addEventListener('input', buscar);
    }
});