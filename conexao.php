<?php
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "aula";

$conexao = mysqli_connect($servidor, $usuario, $senha, $banco);

if(!$conexao) {
    echo "deu erro no banco de dados";
}
?>