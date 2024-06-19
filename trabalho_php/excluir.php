<?php
include 'conexao_bd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    if ($id) {
        $stmt = $conn->prepare("DELETE FROM ranking WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $stmt->close();
            header('Location: ranking.php');
            exit();
        } else {
            $stmt->close();
            die("Erro ao excluir o registro: " . $conn->error);
        }
    } else {
        die("ID inválido.");
    }
} else {
    die("Método de requisição inválido.");
}
?>
