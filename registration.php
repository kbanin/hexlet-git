<?php




$mysql = new mysqli("Localhost", "root","","youtube");
$mysql -> query("SET NAMES 'utf8'");



if ($mysql->connect_error){

    echo 'Error number:'.$mysql->connect_errno.'<br>';
    echo 'Error: '.$mysql->connect_error;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['registerPassword'] ===$_POST['confirmPassword'] ){

$userEmail = $_POST['registerEmail'];
$userPassword = crypt($_POST['registerPassword'], '54321');

$mysql -> query ("INSERT INTO `users`(`email`,`password`)VALUES ( '$userEmail', '$userPassword')");



$mysql->close();

header ("Location:index.php");

}


?>






<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация - Видео Портал</title>
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
                <a class="nav-link" href="index.php">Вход/Регистрация</a>
            </li>
        </ul>
    </div>
</nav>
<!-- Форма регистрации -->
<div class="auth-form"
    <h2>Регистрация</h2>
    <form action= '' method="post">
        <div class="form-group>
            <label for="registerEmail">Электронная почта</label>
            <input type="email" class="form-control" id="registerEmail" placeholder="Введите email" name ="registerEmail" >
        </div>
        <div class="form-group">
            <label for="registerPassword">Пароль</label>
            <input type="password" class="form-control" id="registerPassword" placeholder="Пароль" name ="registerPassword" >
        </div>
        <div class="form-group">
            <label for="confirmPassword">Подтвердите пароль</label>
            <input type="password" class="form-control" id="confirmPassword" placeholder="Подтвердите пароль" name ="confirmPassword" >
        </div>
        <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
    </form>
</div>
</body>
</html>


