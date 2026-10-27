<?php
// Inicia a sessão para ter acesso às variáveis salvas no login
session_start();

// Verificação de Segurança: Se não houver usuário salvo na sessão, redireciona para a tela de login
if (!isset($_SESSION['usuario'])) {
    header("Location: 15d_login.php");
    exit();
}

// Resgata os dados gravados na sessão
$usuario   =$_SESSION['usuario'];
$cor_fundo =$_SESSION['cor_fundo'] ?? '#ffffff'; // Cor padrão caso não tenha sido definida
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <style>
        /* Define a cor de fundo dinamicamente a partir do valor armazenado na sessão */
        body {
            background-color: <?php echo htmlspecialchars($cor_fundo); ?>;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            transition: background-color 0.3s ease;
        }

        .card-perfil {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        h1 {
            margin-top: 0;
            color: #333;
        }

        p {
            color: #555;
            font-size: 16px;
        }

        .btn-sair {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .btn-sair:hover {
            background-color: #c82333;
        }
    </style>
    <a href="15d_logout.php">Logout</a>
</head>
<body>

<div class="card-perfil">
    <!-- Mensagem de boas-vindas exibindo o nome do usuário -->
    <h1>Bem-vindo