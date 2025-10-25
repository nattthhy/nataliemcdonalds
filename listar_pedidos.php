<?php
require_once 'config.php';

// Consultar todos os pedidos com o nome do produto
$sql = "SELECT pedidos.*, produtos.nome AS produto_nome 
        FROM pedidos 
        INNER JOIN produtos ON pedidos.produto_id = produtos.id";


$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Pedidos Acadêmicos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h2 class="text-center mb-4">📋 Lista de Pedidos Acadêmicos</h2>

    <?php if ($result->num_rows > 0) { ?>
      <div class="row row-cols-1 row-cols-md-2 g-4">
        <?php while ($row = $result->fetch_assoc()) { ?>
          <div class="col">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <h5 class="card-title">📘 <?= htmlspecialchars($row['produto_nome']) ?></h5>
                <p><strong>Aluno:</strong> <?= htmlspecialchars($row['nome_cliente']) ?> (<?= htmlspecialchars($row['email']) ?>)</p>
                <p><strong>Curso:</strong> <?= htmlspecialchars($row['curso']) ?></p>
                <p><strong>Tipo de Pedido:</strong> <?= htmlspecialchars($row['tipo_pedido']) ?></p>
                <p><strong>Prazo:</strong> <?= $row['prazo'] ? htmlspecialchars($row['prazo']) : "Não informado" ?></p>
                <p><strong>Pagamento:</strong> <?= htmlspecialchars($row['pagamento']) ?></p>
                <?php if (!empty($row['detalhes'])) { ?>
                  <p><strong>Detalhes:</strong> <?= htmlspecialchars($row['detalhes']) ?></p>
                <?php } ?>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    <?php } else { ?>
      <div class="alert alert-info text-center">Nenhum pedido encontrado.</div>
    <?php } ?>

    <div class="text-center mt-4">
      <a href="listar_produtos.php" class="btn btn-link">⬅ Voltar para produtos</a>
    </div>
  </div>
</body>
</html>
