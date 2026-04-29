<?php
include "conexao.php";

$id_contato = $_GET['id'];

$busca = mysqli_query($conexao, "SELECT * FROM contatos WHERE id = $id_contato");
$dados = mysqli_fetch_array($busca);

if (isset($_POST['atualizar'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];

    $sql_update = "UPDATE contatos SET nome='$nome', telefone='$telefone' WHERE id=$id";
    mysqli_query($conexao, $sql_update);
    
    header("Location: index.php");
}
?>

<h3>Editar contato</h3>
<form method="POST" action="editar.php?id=<?php echo $id_contato; ?>">
    <input type="hidden" name="id" value="<?php echo $dados['id']; ?>">
    
    Nome: <br>
    <input type="text" name="nome" value="<?php echo $dados['nome']; ?>"><br>
    
    Telefone: <br>
    <input type="text" name="telefone" value="<?php echo $dados['telefone']; ?>"><br><br>
    
    <input type="submit" name="atualizar" value="Atualizar">
</form>
<a href="index.php">Cancelar</a>