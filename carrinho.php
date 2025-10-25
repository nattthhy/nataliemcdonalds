<?php
require_once 'config.php';
if(!isset($_SESSION['usuario'])) { header("Location: login.php"); exit; }
$usuario = $_SESSION['usuario'];
if($usuario['tipo'] != 'comprador') { die("Acesso negado."); }

// Atualiza quantidade ou remove item
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(isset($_POST['update'])){
        $id = intval($_POST['id']);
        $quantidade = intval($_POST['quantidade']);
        if($quantidade>0){
            $stmt = $conn->prepare("UPDATE carrinho SET quantidade=? WHERE id=? AND comprador_id=?");
            $stmt->bind_param("iii",$quantidade,$id,$usuario['id']);
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("DELETE FROM carrinho WHERE id=? AND comprador_id=?");
            $stmt->bind_param("ii",$id,$usuario['id']);
            $stmt->execute();
        }
    }
    if(isset($_POST['finalizar'])){
        // Mover todos os itens do carrinho para pedidos
        $stmt = $conn->prepare("INSERT INTO pedidos (produto_id, comprador_id, quantidade) SELECT produto_id, comprador_id, quantidade FROM carrinho WHERE comprador_id=?");
        $stmt->bind_param("i",$usuario['id']);
        $stmt->execute();
        // Limpa carrinho
        $stmt = $conn->prepare("DELETE FROM carrinho WHERE comprador_id=?");
        $stmt->bind_param("i",$usuario['id']);
        $stmt->execute();
        echo "<script>alert('Pedido finalizado!'); window.location='listar_produtos.php';</script>";
        exit;
    }
}

// Busca itens do carrinho
$stmt = $conn->prepare("SELECT c.id as carrinho_id, p.* , c.quantidade FROM carrinho c JOIN produtos p ON c.produto_id=p.id WHERE c.comprador_id=?");
$stmt->bind_param("i",$usuario['id']);
$stmt->execute();
$cart = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <style>
     body {
    background-image: url('pedido.jpg');
    background-size: 100%; 
    background-repeat: no-repeat;
    background-position: center;
} 
    
</style>
<meta charset="UTF-8">
<title>Carrinho - Marketplace Acadêmico</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="container" style="max-width:900px;">

      <h2 class="mb-4 text-white center ">🛒 Carrinho de Compras</h2>

      <?php if($cart->num_rows==0){ ?>
        <p class="text-center">Seu carrinho está vazio.</p>
      <?php } else { ?>
        <form method="POST">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Produto</th><th>Preço</th><th>Quantidade</th><th>Total</th><th>Ações</th>
              </tr>
            </thead>
            <tbody>
            <?php 
              $total_geral = 0;
              while($item = $cart->fetch_assoc()):
                $total = $item['preço'] * $item['quantidade'];
                $total_geral += $total;
            ?>
              <tr>
                <td><?= htmlspecialchars($item['nome']) ?></td>
                <td>R$ <?= number_format($item['preço'],2,',','.') ?></td>
                <td>
                  <input type="number" name="quantidade" value="<?= $item['quantidade'] ?>" min="0" class="form-control" style="width:80px;">
                  <input type="hidden" name="id" value="<?= $item['carrinho_id'] ?>">
                </td>
                <td>R$ <?= number_format($total,2,',','.') ?></td>
                <td>
                  <button type="submit" name="update" class="btn btn-primary btn-sm">Atualizar</button>
                </td>
              </tr>
            <?php endwhile; ?>
              <tr>
                <td colspan="3" class="text-end fw-bold">Total Geral</td>
                <td colspan="2" class="fw-bold">R$ <?= number_format($total_geral,2,',','.') ?></td>
              </tr>
            </tbody>
          </table>
          <button type="submit" name="finalizar" class="btn btn-success w-100">Finalizar Pedido</button>
        </form>
      <?php } ?>

      <p class="mt-3 text-white center"><a href="listar_produtos.php">Voltar para produtos</a></p>
    </div>
  </div>
</body>
</html>