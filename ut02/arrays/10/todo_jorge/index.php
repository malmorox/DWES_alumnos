<?php

define('FILE_NAME','store.dat');

if(file_exists(FILE_NAME)){
    $datos = file_get_contents(FILE_NAME);
    $todo = unserialize($datos);
}else{
    $todo = [];
}

function persistencia(array $info){
    $datos= serialize($info);
    file_put_contents(FILE_NAME, $datos);
}

if(isset($_POST['elemento'])){
    $elemento = $_POST['elemento'];
    $todo []= $elemento;
    persistencia($todo);
}

if(isset($_POST['delete'])){
    $id_para_borrar = $_POST['delete'];
    array_splice($todo, $id_para_borrar, 1);
    persistencia($todo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        form {
            display: inline;
        }
    </style>
</head>
<body>
    <h1>TODO: Ejercicios de DWES</h1>
    <form action="index.php" method="post">
        <input type="text" name="elemento" placeholder="Cosa por hacer..."><br>
        <input type="submit" value="Añadir">
    </form>
    <ul>
        <?php foreach($todo as $k => $e) { ?>
            <li><?=$e?><form action="index.php" method="post"><input type="hidden" value="<?=$k?>" name="delete"><input type="submit" value="X"></form></li>
        <?php } ?>
    </ul>
</body>
</html>