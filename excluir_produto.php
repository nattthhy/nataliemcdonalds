<?php
require_once 'config.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // só admin ou anunciante podem excluir
    if ($_SESSION['usuario']['tipo'] == 'admin' || $_SESSION['usuario']['tipo'] == 'anunciante') {
        $stmt = $conn->prepare("DELETE FROM produtos WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo "<script>alert('Produto excluído com sucesso!'); window.location='listar_produtos.php';</script>";
        } else {
            echo "<script>alert('Erro ao excluir produto!'); window.location='listar_produtos.php';</script>";
        }
    } else {
        echo "<script>alert('Você não tem permissão para excluir!'); window.location='listar_produtos.php';</script>";
    }
} else {
    header("Location: listar_produtos.php");
    exit;
}
?>
