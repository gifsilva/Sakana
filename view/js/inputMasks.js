// Formata o CPF enquanto o usuário digita, adicionando pontos e hífen.
function formatCpf(value) {
    const digits = value.replace(/\D/g, '').slice(0, 11);

    if (digits.length <= 3) return digits;
    if (digits.length <= 6) return `${digits.slice(0, 3)}.${digits.slice(3)}`;
    if (digits.length <= 9) return `${digits.slice(0, 3)}.${digits.slice(3, 6)}.${digits.slice(6)}`;
    return `${digits.slice(0, 3)}.${digits.slice(3, 6)}.${digits.slice(6, 9)}-${digits.slice(9)}`;
}

// Converte os dígitos digitados em um valor monetário no formato brasileiro.
function formatCurrency(value) {
    const digits = value.replace(/\D/g, '');
    if (!digits) return '';

    return (Number(digits) / 100).toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Aguarda o carregamento da página antes de buscar e configurar os campos.
document.addEventListener('DOMContentLoaded', function() {
    const cpfInput = document.getElementById('cpf');
    if (cpfInput) {
        // Reaplica a máscara a cada alteração no campo de CPF.
        cpfInput.addEventListener('input', function() {
            cpfInput.value = formatCpf(cpfInput.value);
        });
    }

    const currencyInput = document.getElementById('valorProduto');
    if (currencyInput) {
        // Reaplica a máscara a cada alteração no campo de valor.
        currencyInput.addEventListener('input', function() {
            currencyInput.value = formatCurrency(currencyInput.value);
        });
    }
});