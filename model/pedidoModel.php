<?php

require_once __DIR__ . '/../config/conexao.php';

class PedidoModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::getConn();
    }

    /**
     * Registra um item de pedido para uma mesa.
     * O campo "Valor" guarda o valor total do item (quantidade x valor unitario
     * do produto no momento do pedido), preservando o histórico mesmo que o
     * preço do produto mude depois.
     */
    public function criarPedido($idMesa, $idProduto, $quantidade, $valor)
    {
        try {
            $sql = "INSERT INTO pedido (idProduto, idMesa, Quantidade, Valor)
                    VALUES (:idProduto, :idMesa, :quantidade, :valor)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':idProduto', $idProduto, PDO::PARAM_INT);
            $stmt->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
            $stmt->bindValue(':quantidade', $quantidade, PDO::PARAM_INT);
            $stmt->bindValue(':valor', $valor);
            return $stmt->execute();

        } catch (PDOException $e) {

            return false;
        }
    }

    public function listarPedidosPorMesa($idMesa)
    {
        $sql = "SELECT pe.idPedido,
                       pe.idProduto,
                       p.nomeProduto,
                       p.imgProduto,
                       pe.Quantidade,
                       pe.Valor
                FROM pedido pe
                INNER JOIN produto p ON p.idProduto = pe.idProduto
                WHERE pe.idMesa = :idMesa
                ORDER BY pe.idPedido DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function excluirPedidosPorMesa($idMesa)
    {
        try {
            $sql = "DELETE FROM pedido WHERE idMesa = :idMesa";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
            return $stmt->execute();

        } catch (PDOException $e) {

            return false;
        }
    }
}
