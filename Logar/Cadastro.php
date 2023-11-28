<?php
if (isset($_POST['nome'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $sexo = $_POST['genero'];
    // Criptografar senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $host = 'localhost';
    $db = 'usuários'; 
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
    //verificar se o email já está cadastrado
    if ($user) {
        echo "<script>alert('Email já cadastrado!');window.location.href='index.php';</script>";
        exit;
    }
    //cadastrar
    $sql = "INSERT INTO usuários (nome, email, senha, sexo) VALUES (:nome, :email, :senha, :sexo)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['nome' => $nome, 'email' => $email, 'senha' => $senhaHash, 'sexo' => $sexo]);
    echo "<script>alert('Cadastro realizado com sucesso!');window.location.href='Login.html';</script>";
}
else {
    echo "<script>alert('Erro ao cadastrar!');window.location.href='index.php';</script>";
}

?>