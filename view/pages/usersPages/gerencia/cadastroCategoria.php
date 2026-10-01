<link rel="stylesheet" href="view/css/cardapio.css?v=3">
<h2 class="titulo-form">Cadastrar Categoria do Cardápio</h2>

<form action="<?= app_url('index.php?action=cadastrarCategoria') ?>" method="POST" enctype="multipart/form-data" class="form-grupo cardapio-form">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

    <div class="cardapio-field">
      <label class="form-label" for="nomeCategoria">Nome da categoria</label>
      <input type="text" id="nomeCategoria" name="nomeCategoria" class="form-input" required placeholder="Ex: Entradas">
    </div>

    <div class="cardapio-field cardapio-field-wide">
      <label class="form-label" for="descCategoria">Descrição da categoria</label>
      <input type="text" id="descCategoria" name="descCategoria" class="form-input" required placeholder="Ex: Pratos principais">
    </div>

    <div class="form-separador cardapio-field-wide">
      <label class="form-label" for="fotoCategoria">Foto da categoria</label>
        <div class="file-upload-group">
            <input type="file" id="fotoCategoria" name="fotoCategoria" class="form-inputFile" accept="image/png, image/jpeg, image/webp, image/jpg">
            <label for="fotoCategoria" class="custom-file-upload">Selecionar arquivo</label>
            <span class="file-name">Nenhum arquivo selecionado</span>
        </div>
        <small class="perfil-upload-help">Formatos aceitos: JPG, JPEG, ou PNG. Tamanho máximo: 2MB.</small>
    </div>
    
    <button type="submit" class="btn-primary">Cadastrar categoria</button>

</form>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var input = document.getElementById('fotoCategoria');
    var fileName = document.querySelector('.file-name');

    if (input && fileName) {
      input.addEventListener('change', function() {
        fileName.textContent = input.files.length
          ? input.files[0].name
          : 'Nenhum arquivo selecionado';
      });
    }
  });
</script>