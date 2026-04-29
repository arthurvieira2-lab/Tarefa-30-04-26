<?php
include "conexao.php";

if (isset($_POST['botao'])) {
    $n = $_POST['nome'];
    $t = $_POST['telefone'];

    $query = "INSERT INTO contatos (nome, telefone) VALUES ('$n', '$t')";
    $salvou = mysqli_query($conexao, $query);
    
    if ($salvou) {
        header("Location: index.php");
    } else {
        echo "não deu pra salvar";
    }
}
?>

<h3>Adicionar contato</h3>
<form method="POST" action="criar.php">
    Nome: <br>
    <input type="text" name="nome"><br>
    Telefone: <br>
    <input type="text" name="telefone"><br><br>
    <input type="submit" name="botao" value="Salvar">
</form>
<br>
<a href="index.php">Voltar</a>