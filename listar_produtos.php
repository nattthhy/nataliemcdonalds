<?php
require_once 'config.php';   

if(!isset($_SESSION['usuario'])) { 
    header("Location: login.php"); 
    exit; 
}

$usuario = $_SESSION['usuario'];

// Busca e ordenação
$busca = $_GET['busca'] ?? '';
$ordenar = $_GET['ordenar'] ?? 'id';

$sql = "SELECT * FROM produtos WHERE nome LIKE ?";
if ($ordenar === 'preço_asc') {
    $sql .= " ORDER BY preço ASC";
} elseif ($ordenar === 'preço_desc') {
    $sql .= " ORDER BY preço DESC";
} else {
    $sql .= " ORDER BY id DESC";
}

$stmt = $conn->prepare($sql);
$busca_param = "%$busca%";
$stmt->bind_param("s", $busca_param);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Marketplace Acadêmico</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Barrio&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Barrio', system-ui;
      background-image: url('burguer.webp');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      min-height: 100vh;
    }

    h2 { color: #fff; }
    .container .d-flex span { font-size: 1rem; color: #fff; }

    /* Busca e ordenação */
    .filtro-form input, .filtro-form select {
        margin-right: 10px;
    }

    /* Cards */
    .card {
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
        background-color: rgba(255,255,255,0.95);
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }
    .card img {
        height: 180px;
        object-fit: cover;
        border-bottom: 1px solid #dee2e6;
    }
    .card-title { font-size: 1.1rem; font-weight: 600; color: #343a40; }
    .text-truncate { font-size: 0.9rem; color: #6c757d; }
    .fw-bold { font-weight: 700; }
    .rating { color: #FF9529; font-size: 0.9rem; margin-bottom: 0.5rem; }

    .btn-success { background-color: #28a745; border-color: #28a745; font-weight: 600; }
    .btn-success:hover { background-color: #218838; border-color: #1e7e34; }
    .btn-warning { background-color: #ffc107; border-color: #ffc107; font-weight: 600; }
    .btn-warning:hover { background-color: #e0a800; border-color: #d39e00; }
    .btn-danger { background-color: #dc3545; border-color: #dc3545; font-weight: 600; }
    .btn-danger:hover { background-color: #bb2d3b; border-color: #b02a37; }
    .btn-outline-primary { border-radius: 6px; font-weight: 500; }
    .btn-delete { background-color: #dc3545; color: #fff; border-radius: 6px; font-weight: 500; }
    .btn-delete:hover { background-color: #bb2d3b; color: #fff; }

    @media (max-width: 768px) {
        .card img { height: 150px; }
        .card-title { font-size: 1rem; }
        .text-truncate { font-size: 0.85rem; }
    }
  </style>
</head>
<body>
<div class="container mt-4">

  <!-- Cabeçalho -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center">
      <img src="logo.jpeg" alt="Logo" style="height:80px;" class="me-3">
      <h2 class="mb-0">Natalie McDonald</h2>
    </div>
    <div>
      <span class="me-2">Olá, <strong><?= htmlspecialchars($usuario['nome']) ?></strong> (<?= htmlspecialchars($usuario['tipo']) ?>)</span>
      <?php if($usuario['tipo']=='admin' || $usuario['tipo']=='anunciante'): ?>
        <a href="teste.php" class="btn btn-success me-2">+ Anunciar Produto</a>
      <?php endif; ?>
      <?php if($usuario['tipo']=='comprador'): ?>
        <a href="carrinho.php" class="btn btn-warning me-2">🛒 Carrinho</a>
      <?php endif; ?>
      <a href="logout.php" class="btn btn-danger">Sair</a>
    </div>
  </div>

  <!-- Busca e Ordenação -->
  <form method="GET" class="filtro-form mb-4 d-flex">
      <input type="text" name="busca" class="form-control" placeholder="Buscar produto..." value="<?= htmlspecialchars($busca) ?>">
      <select name="ordenar" class="form-select">
          <option value="id" <?= ($ordenar=='id')?'selected':'' ?>>Mais recentes</option>
          <option value="preço_asc" <?= ($ordenar=='preço_asc')?'selected':'' ?>>Preço: menor para maior</option>
          <option value="preço_desc" <?= ($ordenar=='preço_desc')?'selected':'' ?>>Preço: maior para menor</option>
      </select>
      <button type="submit" class="btn btn-primary">Filtrar</button>
  </form>

  <!-- Lista de Produtos -->
  <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
    <?php while($row = $result->fetch_assoc()):
      $rating = rand(3,5);
      $stars = str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
      $ext = strtolower(pathinfo($row['imagem'], PATHINFO_EXTENSION));
      $imgTag = ($ext === 'pdf') 
        ? '<img src="https://via.placeholder.com/300x180.png?text=PDF" class="card-img-top">' 
        : '<img src="'.htmlspecialchars($row['imagem']).'" class="card-img-top">';
    ?>
    <div class="col">
      <div class="card h-100 shadow-sm">
        <?= $imgTag ?>
        <div class="card-body d-flex flex-column">
          <h5 class="card-title"><?= htmlspecialchars($row['nome']) ?></h5>
          <p class="text-truncate"><?= htmlspecialchars($row['descrição']) ?></p>
          <p class="fw-bold text-success mb-1">R$ <?= number_format($row['preço'],2,',','.') ?></p>
          <p class="rating mb-2"><?= $stars ?></p>

          <?php if($usuario['tipo']=='comprador'): ?>
            <form method="POST" action="adicionar_carrinho.php">
              <input type="hidden" name="produto_id" value="<?= $row['id'] ?>">
              <input type="number" name="quantidade" value="1" min="1" class="form-control mb-2">
              <button type="submit" class="btn btn-success mt-auto w-100">Adicionar ao Carrinho</button>
            </form>

          <?php elseif($usuario['tipo']=='admin' || $usuario['tipo']=='anunciante'): ?>
            <div class="d-flex flex-column mt-auto">
              <a href="<?= htmlspecialchars($row['imagem']) ?>" target="_blank" class="btn btn-outline-primary mb-2">Ver Detalhes</a>
              <?php if ($usuario['tipo']=='admin' || $usuario['email']==$row['usuario_email']): ?>
                <a href="excluir_produto.php?id=<?= $row['id'] ?>" 
                   class="btn btn-delete"
                   onclick="return confirm('Tem certeza que deseja excluir este produto?')">
                  Excluir
                </a>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>

</div>
</body>
</html>
