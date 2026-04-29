<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php
// Configurações do Banco de Dados
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sistema_bancario";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$mensagem = "";

// Lógica de Depósito e Saque
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo = $_POST['tipo'];
    $valor = floatval($_POST['valor']);

    // Calcular saldo atual antes de permitir saque
    $res = $conn->query("SELECT SUM(CASE WHEN tipo = 'deposito' THEN valor ELSE -valor END) as saldo FROM transacoes");
    $row = $res->fetch_assoc();
    $saldo_atual = $row['saldo'] ?? 0;

    if ($valor <= 0) {
        $mensagem = "Erro: O valor deve ser maior que zero.";
    } elseif ($tipo == 'saque' && $valor > $saldo_atual) {
        $mensagem = "Erro: Saldo insuficiente para realizar este saque.";
    } else {
        $stmt = $conn->prepare("INSERT INTO transacoes (tipo, valor) VALUES (?, ?)");
        $stmt->bind_param("sd", $tipo, $valor);
        if ($stmt->execute()) {
            $mensagem = "Operação de " . ucfirst($tipo) . " realizada com sucesso!";
        }
        $stmt->close();
    }
}

// Buscar Saldo Final e Extrato
$res_extrato = $conn->query("SELECT * FROM transacoes ORDER BY data_hora DESC");
$res_saldo = $conn->query("SELECT SUM(CASE WHEN tipo = 'deposito' THEN valor ELSE -valor END) as saldo FROM transacoes");
$saldo_final = $res_saldo->fetch_assoc()['saldo'] ?? 0;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Caixa Eletrônico PHP/MySQL</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; display: flex; justify-content: center; padding: 20px; }
        .container { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 400px; }
        .saldo-box { background: #e7f3ff; padding: 15px; border-radius: 5px; text-align: center; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        input[type="number"], select { width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #28a745; color: white; border: none; cursor: pointer; border-radius: 5px; }
        .extrato { margin-top: 20px; font-size: 0.9em; border-top: 1px solid #ddd; padding-top: 10px; }
        .msg { color: #d9534f; font-weight: bold; margin-bottom: 10px; }
        .saque { color: red; } .deposito { color: green; }
    </style>
</head>
<body>

<div class="container">
    <h2>Caixa Eletrônico</h2>
    
    <div class="saldo-box">
        <strong>Saldo Atual:</strong><br>
        <span style="font-size: 1.5em;">R$ <?php echo number_format($saldo_final, 2, ',', '.'); ?></span>
    </div>

    <?php if ($mensagem) echo "<p class='msg'>$mensagem</p>"; ?>

    <form method="POST">
        <div class="form-group">
            <label>Tipo de Operação:</label>
            <select name="tipo">
                <option value="deposito">Depósito</option>
                <option value="saque">Saque</option>
            </select>
        </div>
        <div class="form-group">
            <label>Valor (R$):</label>
            <input type="number" name="valor" step="0.01" required>
        </div>
        <button type="submit">Confirmar Operação</button>
    </form>

    <div class="extrato">
        <h3>Extrato de Movimentações</h3>
        <?php while($row = $res_extrato->fetch_assoc()): ?>
            <div style="margin-bottom: 5px; border-bottom: 1px dashed #eee;">
                <span class="<?php echo $row['tipo']; ?>">
                    <?php echo ucfirst($row['tipo']); ?>: R$ <?php echo number_format($row['valor'], 2, ',', '.'); ?>
                </span>
                <br><small><?php echo date('d/m/Y H:i', strtotime($row['data_hora'])); ?></small>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
    
</body>
</html>