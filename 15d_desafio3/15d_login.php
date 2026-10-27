<?php
// Inicia a sessão para salvar as informações do usuário
session_start();

// Configurações do Banco de Dados
$host    = 'localhost';
$db_user = 'root';     // Geralmente 'root' no XAMPP/WAMP
$db_pass = 'Senai@118';         // Senha em branco por padrão no XAMPP (ou 'root' no MAMP)
$db_name = 'exercicio';

// Conexão com o banco de dados MySQL
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$mensagem_erro = "";

// Verifica se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha   = trim($_POST['senha'] ?? '');
    $cor     = $_POST['cor_fundo'] ?? '#ffffff';

    if (!empty($usuario) && !empty($senha)) {
        // Consulta o banco de dados usando Prepared Statement para evitar SQL Injection
        $stmt = $conn->prepare("SELECT id, usuario, senha FROM usuarios WHERE usuario = ? LIMIT 1");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows === 1) {
            $dados_usuario = $resultado->fetch_assoc();

            // Verifica a senha. 
            // Obs: Se as senhas no seu banco estiverem em texto puro, troque por: if ($senha === $dados_usuario['senha'])
            if (password_verify($senha, $dados_usuario['senha']) || $senha === $dados_usuario['senha']) {
                
                // Salva os dados na Sessão
                $_SESSION['usuario_id']  = $dados_usuario['id'];
                $_SESSION['usuario']     = $dados_usuario['usuario'];
                $_SESSION['cor_fundo']   = $cor;

                // Redireciona para a página de perfil
                header("Location: 15d_perfil.php");
                exit();
            } else {
                $mensagem_erro = "Usuário ou senha incorretos!";
            }
        } else {
            $mensagem_erro = "Usuário ou senha incorretos!";
        }

        $stmt->close();
    } else {
        $mensagem_erro = "Por favor, preencha todos os campos!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-card {
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 320px;
        }
        h2 {
            margin-top: 0;
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="color"] {
            width: 100%;
            height: 40px;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .erro {
            color: red;
            background-color: #ffe6e6;
            padding: 8px;
            border: 1px solid red;
            border-radius: 4px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h2>Login</h2>

    <!-- Exibe a mensagem de erro se o login/senha falhar -->
    <?php if (!empty($mensagem_erro)): ?>
        <div class="erro"><?php echo htmlspecialchars($mensagem_erro); ?></div>
    <?php endif; ?>

    <form method="POST" action="15d_login.php">
        <div class="form-group">
            <label for="usuario">Usuário:</label>
            <input type="text" id="usuario" name="usuario" required>
        </div>

        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
        </div>

        <div class="form-group">
            <label for="cor_fundo">Cor de Fundo:</label>
            <input type="color" id="cor_fundo" name="cor_fundo" value="#ffffff">
        </div>

        <button type="submit">Entrar</button>
    </form>
</div>

</body>
</html>