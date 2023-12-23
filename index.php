<?php
session_start();


$mysql = new mysqli("Localhost", "root", "", "youtube");
$mysql->query("SET NAMES 'utf8'");

if ($mysql->connect_error){

    echo 'Error number:'.$mysql->connect_errno.'<br>';
    echo 'Error: '.$mysql->connect_error;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $mysql->query("SELECT `id`,`email`,`password` FROM `users` WHERE `email` = '$email' ");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];
        $_SESSION['user_id'] = $row['id'];
        $mysql->close();


 if (password_verify($password, $hashedPassword)) {

 header("Location:video.php");
exit;
} else {
echo "Неверная почта или пароль";
}
    }

}

?>



<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авторизация - Видео Портал</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .auth-form {
            width: 300px;
            margin: 50px auto;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Видео Портал</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Главная</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Загрузить Видео</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="registration.php">Вход/Регистрация</a>
            </li>
        </ul>
    </div>
</nav>
<!-- Форма авторизации -->
<div class="auth-form">
    <h2>Авторизация</h2>
    <form action= '' method="post">
        <div class="form-group">
            <label for="loginEmail">Электронная почта</label>
            <input type="email" class="form-control" id="loginEmail" placeholder="Введите email" name ="email">
        </div>
        <div class="form-group">
            <label for="loginPassword">Пароль</label>
            <input type="password" class="form-control" id="loginPassword" placeholder="Пароль" name = "password">
        </div>
        <button type="submit" class="btn btn-primary">Войти</button>
    </form>
</div>
</body>
</html>












