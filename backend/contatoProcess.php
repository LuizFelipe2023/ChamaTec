<?php
header('Content-Type: application/json');

$msg = '';
$status = 'error';  

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_NUMBER_INT);
    $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (empty($nome) || empty($email) || empty($telefone) || empty($tipo) || empty($descricao)) {
        $msg = "Todos os campos são obrigatórios!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Email inválido!";
    } else {
        $stmt = $conn->prepare('INSERT INTO chamados(nome,email,telefone,tipo,descricao) VALUES(?,?,?,?,?)');
        $stmt->bindParam(1, $nome, PDO::PARAM_STR);
        $stmt->bindParam(2, $email, PDO::PARAM_STR);
        $stmt->bindParam(3, $telefone, PDO::PARAM_STR);
        $stmt->bindParam(4, $tipo, PDO::PARAM_INT);
        $stmt->bindParam(5, $descricao, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $msg = "Chamado inserido com sucesso!";
            $status = 'success';  
        } else {
            $msg = "Erro ao inserir o chamado!";
        }
    }

    echo json_encode(['msg' => $msg, 'status' => $status]);  
}
