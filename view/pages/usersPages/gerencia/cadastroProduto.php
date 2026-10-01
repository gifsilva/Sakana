<link rel="stylesheet" href="view/css/cardapio.css?v=3">
<script src="<?= app_url('view/js/inputMasks.js') ?>" defer></script>
<h2 class="titulo-form">Cadastrar Itens do Cardápio</h2>

<form action="<?= app_url('index.php?action=cadastrarProduto') ?>" method="POST" enctype="multipart/form-data" class="form-grupo cardapio-form">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

  <div class="cardapio-field">
    <label class="form-label" for="nomeProduto">Nome do produto</label>
    <input type="text" id="nomeProduto" name="nomeProduto" class="form-input" required placeholder="Ex: Temaki">
  </div>

  <div class="cardapio-field cardapio-field-wide">
    <label class="form-label" for="descProduto">Descrição do produto</label>
    <input type="text" id="descProduto" name="descProduto" class="form-input" required placeholder="Ex: Temaki de salmão com cream cheese">
  </div>
    
    <div class="form-separador cardapio-field-wide">
      <label class="form-label" for="fotoProduto">Foto do produto</label>
        <div class="file-upload-group">
            <input type="file" id="fotoProduto" name="fotoProduto" class="form-inputFile" accept="image/png, image/jpeg, image/webp, image/jpg">
            <label for="fotoProduto" class="custom-file-upload">Selecionar arquivo</label>
            <span class="file-name">Nenhum arquivo selecionado</span>
        </div>
        <small class="perfil-upload-help">Formatos aceitos: JPG, JPEG, PNG ou WEBP. Tamanho máximo: 2MB.</small>
    </div>

    <div class="cardapio-field">
      <label class="form-label" for="idCategoria">Categoria</label>
      <select id="idCategoria" name="idCategoria" class="form-input" required>
        <option value="">Selecione...</option>
        <?php foreach($listaCategorias as $c): ?>
          <option value="<?=$c['idCategoria']?>">
            <?= $c['nomeCategoria']?></option>
        <?php endforeach; ?>
      </select>
    </div>
    
    <div class="cardapio-field">
      <label class="form-label" for="valorProduto">Valor</label>
            <input type="text" id="valorProduto" name="valorProduto" class="form-input" required
              inputmode="decimal" autocomplete="off" placeholder="Ex: 25,90">
    </div>

    <button type="submit" class="btn-primary cardapio-submit">Cadastrar produto</button>

</form>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var input = document.getElementById('fotoProduto');
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