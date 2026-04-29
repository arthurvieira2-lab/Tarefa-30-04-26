<?php
include "conexao.php";

echo "<h2>Meus Contatos</h2>";
echo "<a href='criar.php'>+ Novo Contato</a><br><br>";

$sql = "SELECT * FROM contatos";
$resultado = mysqli_query($conexao, $sql);

while ($linha = mysqli_fetch_array($resultado)) {
    echo "Nome: " . $linha["nome"] . " | ";
    echo "Tel: " . $linha["telefone"] . "<br>";
    
    echo "<a href='editar.php?id=" . $linha["id"] . "'>Editar</a> - ";
    echo "<a href='deletar.php?id=" . $linha["id"] . "'>Apagar</a><br><br>";
}
?>