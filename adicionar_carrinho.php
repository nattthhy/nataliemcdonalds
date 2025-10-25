<?php
require_once 'config.php';
if(!isset($_SESSION['usuario'])) { header("Location: login.php"); exit; }
$usuario = $_SESSION['usuario'];
if($usuario['tipo'] != 'comprador') { die("Acesso negado."); }

if($_SERVER["REQUEST_METHOD"]==='POST'){
    $produto_id = intval($_POST['produto_id']);
    $quantidade = intval($_POST['quantidade']);
    if($quantidade < 1) $quantidade = 1;

    // Verifica se produto já está no carrinho
    $stmt = $conn->prepare("SELECT id, quantidade FROM carrinho WHERE comprador_id=? AND produto_id=?");
    $stmt->bind_param("ii",$usuario['id'],$produto_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if($res->num_rows > 0){
        $row = $res->fetch_assoc();
        $nova_quantidade = $row['quantidade'] + $quantidade;
        $stmt = $conn->prepare("UPDATE carrinho SET quantidade=? WHERE id=?");
        $stmt->bind_param("ii",$nova_quantidade,$row['id']);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO carrinho (comprador_id, produto_id, quantidade) VALUES (?,?,?)");
        $stmt->bind_param("iii",$usuario['id'],$produto_id,$quantidade);
        $stmt->execute();
    }

    header("Location: carrinho.php");
    exit;
}
?>
