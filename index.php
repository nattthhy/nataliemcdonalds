<?php

require_once 'config.php';

if (isset($_SESSION['usuario'])) {
    header("Location: listar_produtos.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>site</title>
    <style>
         @import url('https://fonts.googleapis.com/css2?family=Barrio&display=swap');

        body{
             font-family: "Barrio", system-ui;
             background:url(./McDonald.jpeg);
                background-size: cover;
            background-position: center;
             text-align: center;
             color:white;

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
    <h1>Bem vindo ao <br>Natalie mcdonalds</h1>
    <div class="box">
        <a href="pages/mclog.php">login</a>
        <a href=></a>
    </div>
</body>
</html>