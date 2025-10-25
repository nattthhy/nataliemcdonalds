<?php

require_once 'config.php';

if (isset($_SESSION['usuario'])) {
    header("Location: listar_produtos.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Home | Marketplace Acadêmico</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

 <style>
         @import url('https://fonts.googleapis.com/css2?family=Barrio&display=swap');

        body{
            
    font-family: "Barrio", system-ui;
             background:url(./McDonald.jpeg);
                 background-size:cover, 50% 50%;;
            background-position: center;
             text-align: center;
             color:white;
}


        }
        h1{
            font-size: 100px;
        }
        .box{
            position:absolute;
            top:50%;
            left:50%;
            transform:translate(-50%,-50%);
            background-color:(0,0,0,6);
            padding: 30px;
            border-radius:15px;
        }
        a{
            text-decoration:none;
            color:white;
            border:3px solid;
            border-radius:15px;
            padding:30px;
        }
        a:hover{
            background-color:brown;
        }
    </style>

</head>
<body>

<div class="overlay"></div>

<div class="content">
    <h1>Bem vindo ao <br>Natalie mcdonalds</h1>
    <p>Encontre livros, apostilas e materiais acadêmicos de forma prática!</p>
     <div class="box">
    <a href="login.php">login</a>
</div>
    


</body>
</html>