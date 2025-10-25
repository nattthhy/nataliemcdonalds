<?php
session_start(); // OBRIGATÓRIO para login funcionar

$host = "localhost";
$user = "root";
$pass = "";
$db = "marketplace";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Erro de conexão: " . $conn->connect_error);
?>
