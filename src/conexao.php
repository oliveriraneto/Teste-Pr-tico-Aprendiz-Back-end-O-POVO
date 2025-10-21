<?php
    $hostname = "localhost";
    $bancodedados = "biblioteca";
    $usuario = "root";
    $senha = "";

    $mysqli = new mysqli($hostname, $usuario, $senha, $bancodedados);

    if ($mysqli->connect_errno) {
        echo "Falha na conexão: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    } else {
        echo "Conectado com banco de dados";
    }
?>
