<?php
require_once "conexao.php";

$imagens = Conexao::listarImagem();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Relatório de Imagens</h2>
    <a href="imagemForm.php" class="btn-voltar">Voltar para Upload</a>
    <table>
        <thead>
            <th>Nome</th>
            <th>Endereço da pasta</th>
            <th>Imagem pasta</th>
        </thead>
        <tbody>
            <?php foreach($imagens as $img);?>
            <tr>
                <td><?$img('nome');?></td>
                <td><?$img('endpasta');?></td>
                <td>
                    <img src="<?php echo $img('endpasta');?>"width="120" alt ="Foto">
                </td>
            </tr>
            <?php endforeach; ?>

        </tbody>
    </table>

</body>
</html>
    