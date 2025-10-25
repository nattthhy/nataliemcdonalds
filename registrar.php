<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $tipo = $_POST['tipo'];

    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $email, $senha, $tipo);

    if ($stmt->execute()) {
        echo "<script>alert('Usuário registrado com sucesso!'); window.location='login.php';</script>";
        exit;
    } else {
        $erro = $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Registrar | Marketplace Acadêmico</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
@import url('https://fonts.googleapis.com/css2?family=Barrio&display=swap');
* {
  margin:0;
  padding:0;
  box-sizing: border-box;
}
body {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-image: linear-gradient(to right,#f03628,#63120d);
  background-size: cover;
  background-position: center;
}
.container {
  width: 420px;
  background-color: transparent;
  border: 2px solid rgba(255, 255, 255,.2);
  border-radius: 20px;
  color: rgb(199, 187, 19);
  padding: 30px 40px;
  box-shadow: 0 0 10px rgba(0,0, 0, .2);
  font-family: "Barrio", system-ui;
  font-weight: 400;
  font-style: normal;
}
.container h1 {
  font-size: 36px;
  text-align: center;
}
.nomecaixa {
  position: relative;
  height: 30px;
  margin: 20px 0;
  width: 90%;
}
.nomecaixa input, .nomecaixa select {
  width: 90%;
  height: 80%;
  background-color: white;
  border: 2px solid;
  border-radius: 30px;
  outline: none;
  font-size: 15px;
  color: yellow;
  padding: 10px 45px 10px 20px;
}
.nomecaixa i {
  position: absolute;
  right: 20px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 20px;
  color: black;
}
.submit {
  width: 100%;
  height: 40px;
  background-color:  rgb(238, 216, 93);
  border: none;
  border-radius: 40px;
  cursor: pointer;
  font-size: 16px;
  color: rgb(216, 197, 24);
  font-weight: 600;
}
.submit:hover {
  background-color: transparent;
  border: 2px solid yellow;
  color: black;
}
.erro {
  background-color: rgba(255, 0, 0, 0.2);
  color: #ffcccc;
  padding: 8px;
  border-radius: 8px;
  margin-bottom: 10px;
  text-align: center;
}
</style>
</head>

<body>
<div class="container">
  <h1>Registrar</h1>

  <?php if (!empty($erro)): ?>
    <div class="erro"><?= htmlspecialchars($erro) ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <div class="nomecaixa">
      <input type="text" name="nome" placeholder="Seu nome completo" required>
      <i class="fa-solid fa-user"></i>
    </div>

    <div class="nomecaixa">
      <input type="email" name="email" placeholder="Seu e-mail" required>
      <i class="fa-solid fa-envelope"></i>
    </div>

    <div class="nomecaixa">
      <input type="password" name="senha" placeholder="Crie uma senha" required>
      <i class="fa-solid fa-lock"></i>
    </div>

    <div class="nomecaixa">
      <select name="tipo" required>
        <option value="">Selecione o tipo de conta</option>
        <option value="comprador">🛒 Comprador</option>
        <option value="anunciante">📢 Anunciante</option>
      </select>
      <i class="fa-solid fa-user-gear"></i>
    </div>

    <button type="submit" class="submit">Cadastrar</button>

    <p style="text-align:center; margin-top:20px;">
      Já tem conta?
      <a href="login.php" style="color:yellow; text-decoration:none;">Entrar</a>
    </p>
  </form>
</div>
</body>
</html>
