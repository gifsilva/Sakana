<?php
$mesa = $mesa ?? null;
$listaPedidos = $listaPedidos ?? [];
$totalGeral = $totalGeral ?? 0;
$mesaDisponivel = ($mesa['status'] ?? '') === 'Disponivel';
?>

<div class="consulta-container">

    <div class="consulta-header consulta-header-acoes">
        <h1 class="consulta-titulo">
            Resumo - Mesa <?= htmlspecialchars($mesa['numeromesa'] ?? '') ?>

            <?php if ($mesaDisponivel): ?>
                <span class="mesa-status disponivel">Disponível</span>
            <?php else: ?>
                <span class="mesa-status indisponivel">Indisponível</span>
            <?php endif; ?>
        </h1>

        <div class="consulta-header-botoes">

            <?php if ($mesaDisponivel): ?>
                <form action="<?= app_url('index.php?action=fecharMesaPedido') ?>" method="POST"
                    onsubmit="return confirm('Deseja realmente fechar esta mesa?');">
                    <input type="hidden" name="idMesa" value="<?= htmlspecialchars($mesa['idmesa'] ?? '') ?>">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    <button type="submit" class="btn-fechar">Fechar mesa</button>
                </form>
            <?php endif; ?>

            <a href="<?= app_url('index.php?action=logadoGerencia&page=pedidos') ?>" class="btn-secondary">
                Voltar
            </a>

        </div>
    </div>

    <div class="consulta-tabela-wrapper">
        <table class="consulta-tabela">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Valor</th>
                </tr>
            </thead>

            <tbody>
                <?php if (count($listaPedidos) > 0): ?>
                    <?php foreach ($listaPedidos as $pedido): ?>
                        <tr class="tabela-linha">
                            <td class="celula-nome">
                                <span><?= htmlspecialchars($pedido['nomeProduto']) ?></span>
                            </td>
                            <td><?= htmlspecialchars($pedido['Quantidade']) ?></td>
                            <td>R$ <?= number_format($pedido['Valor'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr class="tabela-vazia">
                        <td colspan="3">Nenhum pedido registrado para esta mesa.</td>
                    </tr>
                <?php endif; ?>
            </tbody>

            <?php if (count($listaPedidos) > 0): ?>
                <tfoot>
                    <tr class="tabela-total">
                        <td colspan="2">Total</td>
                        <td>R$ <?= number_format($totalGeral, 2, ',', '.') ?></td>
                    </tr>
                </tfoot>
            <?php endif; ?>
        </table>
    </div>

</div>
