<?php
require_once 'config.php';

// Buscar informações do produto/serviço pelo ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $produto = $conn->query("SELECT * FROM produtos WHERE id = $id")->fetch_assoc();
}

$mensagem = "";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $produto_id = $_POST['produto_id'];
    $nome_cliente = $_POST['nome_cliente'];
    $email = $_POST['email'];
    $curso = $_POST['curso'];
    $tipo_pedido = $_POST['tipo_pedido'];
    $prazo = $_POST['prazo'];
    $pagamento = $_POST['pagamento'];
    $detalhes = $_POST['detalhes'];

    // ✅ Agora inserimos em todos os campos da tabela
    $stmt = $conn->prepare("INSERT INTO pedidos 
        (produto_id, nome_cliente, email, curso, tipo_pedido, prazo, pagamento, detalhes) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("isssssss", 
        $produto_id, 
        $nome_cliente, 
        $email, 
        $curso, 
        $tipo_pedido, 
        $prazo, 
        $pagamento, 
        $detalhes
    );

    if ($stmt->execute()) {
        $mensagem = '<div class="alert alert-success text-center mt-3">✅ Pedido enviado com sucesso!</div>';
    } else {
        $mensagem = '<div class="alert alert-danger text-center mt-3">❌ Erro ao enviar pedido.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Fazer Pedido Acadêmico</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <?php if (!empty($produto)) { ?>
      <div class="card shadow-sm p-4">
        <h3>📘 Pedido: <?= htmlspecialchars($produto['nome']) ?></h3>
        <p class="text-muted"><?= htmlspecialchars($produto['descrição']) ?></p>
        <p class="text-success fw-bold">💰 R$ <?= number_format($produto['preço'], 2, ',', '.') ?></p>
        <hr>

        <form method="POST">
          <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">

          <div class="mb-3">
            <label class="form-label">Nome Completo</label>
            <input type="text" name="nome_cliente" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">email </label>
            <input type="email" name="email" class="form-control" placeholder="ex: aluno@universidade.edu" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Curso</label>
            <input type="text" name="curso" class="form-control" placeholder="Ex: Engenharia, Direito, Medicina" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Tipo de Pedido</label>
            <select name="tipo_pedido" class="form-select" required>
              <option value="">Selecione...</option>
              <option>Compra de material</option>
              <option>Serviço acadêmico (Revisão, Digitação, Design)</option>
              <option>Aula ou monitoria</option>
              <option>Outro</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Prazo desejado</label>
            <input type="date" name="prazo" class="form-control">
          </div>

          <div class="mb-3">
            <label class="form-label">Forma de Pagamento</label>
            <select name="pagamento" class="form-select" required>
              <option>Pix</option>
              <option>Dinheiro</option>
              <option>Troca de material</option>
              <option>Outro</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Detalhes / Observações</label>
            <textarea name="detalhes" class="form-control" rows="3" placeholder="Ex: preciso até sexta, posso retirar no campus..."></textarea>
          </div>

          <button class="btn btn-success w-100">Enviar Pedido Acadêmico</button>
        </form>

        <?= $mensagem ?>
      </div>
    <?php } else { ?>
      <div class="alert alert-danger text-center mt-5">❌ Produto ou serviço não encontrado.</div>
    <?php } ?>

    <div class="text-center mt-3">
      <a href="listar_produtos.php" class="btn btn-link">⬅ Voltar para lista</a>
    </div>
  </div>
</body>
</html>
