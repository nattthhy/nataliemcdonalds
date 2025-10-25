<?php
require_once 'config.php';

if (isset($_SESSION['usuario'])) {
    header("Location: listar_produtos.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();
        if (password_verify($senha, $user['senha'])) {
            $_SESSION['usuario'] = [
                'id'=>$user['id'],
                'nome'=>$user['nome'],
                 'email' => $usuario['email'],
                'tipo'=>$user['tipo']
            ];
            header("Location: listar_produtos.php");
            exit;
        }
    }
    $erro = "Email ou senha incorretos!";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Login | Marketplace Acadêmico</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
*{
    margin:0;
    padding:0;
    box-sizing: border-box;
}
body{
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: url(login.jpg);
    background-size: cover;
    background-position: center;
}
.container{
    width: 420px;
    background-color: transparent;
    border: 2px solid rgba(255, 255, 255,.2);
    border-radius: 20px;
    color: rgb(131, 24, 16);
    padding: 30px 40px;
    box-shadow: 0 0 10px rgba(0,0, 0, .2);
    font-family: "Barrio", system-ui;
    font-weight: 400;
    font-style: normal;
}
.container h1{
    font-size: 36px;
    text-align: center;
}
.input-box{
 position: relative;
 width: 90%;
 height: 40px;
 margin: 30px 0;
}
.input-box input{
 width: 90%;
 height: 80%;
 background-color: transparent;
 border: 2px solid;
 border-radius: 30px;
 outline: none;
 font-size: 15px;
 color: brown;
 padding: 10px 45px 10px 20px;
}
.input-box i{
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 20px;
    color: brown;
}
.lembre{
    display: flex;
    justify-content: space-between;
    margin: -15px 0 15px;
}
.lembre label input{
    accent-color: brown;
    margin-right: 5px;
}
.lembre a { 
    text-decoration: none;
    color: brown;
}
.lembre a:hover{
    text-decoration: underline;
}
.login{
    width: 100%;
    height: 40px;
    background-color: rgb(238, 216, 93);
    border: none;
    border-radius: 40px;
    cursor: pointer;
    font-size: 16px;
    color: brown;
    font-weight: 600;
}
.login:hover{
    background-color: transparent;
    border: 2px solid brown;
    color: brown;
}
.erro {
    background-color: rgba(255, 0, 0, 0.2);
    color: darkred;
    padding: 8px;
    border-radius: 8px;
    margin-bottom: 10px;
    text-align: center;
}
</style>
</head>

<body>
<div class="container">
    <h1>Login</h1>

    <?php if (!empty($erro)): ?>
        <div class="erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="input-box">
            <input type="email" name="email" placeholder="Digite seu e-mail" required>
            <i class="fa-solid fa-envelope"></i>
        </div>

        <div class="input-box">
            <input type="password" name="senha" placeholder="Digite sua senha" required>
            <i class="fa-solid fa-lock"></i>
        </div>

        <div class="lembre">
            <label><input type="checkbox">Lembrar</label>
            <a href="#">Esqueci a senha</a>
        </div>

        <button type="submit" class="login">Entrar</button>

        <p style="text-align:center; margin-top:20px;">Não tem conta?
            <a href="registrar.php" style="color:brown; text-decoration:none;">Cadastre-se</a>
        </p>
    </form>
</div>
</body>
</html>s