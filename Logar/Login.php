<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $host = 'localhost';
    $db = 'usuários'; // Certifique-se de que o nome do banco de dados está correto
    $user = 'root';
    $pass = ''; 
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_PERSISTENT => false,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }

    $sql = "SELECT senha FROM usuários WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    session_start();

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['email'] = $email;
        echo "<script>alert('Login realizado com sucesso!');</script>";
        header('Location: ../Home/Home.php');
        exit;
    } else {
        echo "<script>alert('Email e/ou senha incorretos!');</script>";
        exit;
    }
} else {
    echo "<script>alert('Erro ao logar!');</script>";
    exit;
}

?>
