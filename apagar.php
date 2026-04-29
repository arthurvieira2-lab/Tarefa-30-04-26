<?php
include "conexao.php";

$id = $_GET['id'];

$sql = "DELETE FROM contatos WHERE id = $id";
mysqli_query($conexao, $sql);

header("Location: index.php");
?>