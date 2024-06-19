<?php
session_start();
include 'conexao_bd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $respostas = filter_input(INPUT_POST, 'respostas', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

    if ($nome && $respostas) {
        $_SESSION['respostas'] = $respostas;
        $_SESSION['nome'] = $nome;
        header('Location: resultado.php');
        exit();
    } else {
        $error = "Por favor, preencha todas as perguntas.";
    }
}

$sql = "SELECT * FROM perguntas";
$result = $conn->query($sql);
$perguntas = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $perguntas[] = $row;
    }
} else {
    die("Erro ao buscar perguntas: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perguntas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Perguntas do Quiz</h1>
        <?php if (isset($error)) : ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="perguntas.php">
            <div class="form-group">
                <label for="nome">Seu Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <?php foreach ($perguntas as $index => $pergunta) : ?>
                <div class="mb-4">
                    <h4><?php echo htmlspecialchars($pergunta['enunciado']); ?></h4>
                    <?php for ($i = 1; $i <= 4; $i++) : ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="respostas[<?php echo $pergunta['id']; ?>]" value="<?php echo htmlspecialchars($pergunta['opcao' . $i]); ?>" required>
                            <label class="form-check-label"><?php echo htmlspecialchars($pergunta['opcao' . $i]); ?></label>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php endforeach; ?>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Ver Resultado</button>
            </div>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
