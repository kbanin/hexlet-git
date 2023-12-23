<?php
session_start();
$userId = $_SESSION ['user_id'];


$mysql = new mysqli("Localhost", "root", "", "youtube");
$mysql->query("SET NAMES 'utf8'");

if ($mysql->connect_error){

   echo 'Error number:'.$mysql->connect_errno.'<br>';
   echo 'Error: '.$mysql->connect_error;}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $videoTitle = $_POST['videoTitle'];
    $videoDescription = $_POST['videoDescription'];

    // Путь для сохранения загруженных видео
    $uploadDirectory = 'D:\PHP\OSPanel\domains\myFirstProject\OOP\uploadVideo';
    // Обработка загруженного видео
    $videoFile = $_FILES['videoFile'];
    $videoFileName = $videoFile['name'];
    $videoFilePath = $uploadDirectory .'\\'. $videoFileName;

    // Перемещение загруженного файла в указанную директорию
    if (move_uploaded_file($videoFile['tmp_name'], $videoFilePath)) {
  $escapedLink = mysqli_real_escape_string($mysql,$videoFilePath);



        $mysql->query("INSERT INTO `videos`(`title`,`description`,`url`,`user_id`)VALUES ( '$videoTitle', '$videoDescription','$escapedLink','$userId')");


        $mysql->close();

        header("Location:video.php");
    }

}

?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Загрузка Видео - Видео Портал</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .upload-form {
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<!-- Навигационная панель -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Видео Портал</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="video.php">Главная</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="#">Загрузить Видео</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="registration.php">Вход/Регистрация</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Форма загрузки -->
<div class="container mt-4">
    <div class="upload-form">
        <h2>Загрузить Видео</h2>
        <form action= '' method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="videoTitle">Название видео</label>
                <input type="text" class="form-control" id="videoTitle" placeholder="Введите название видео" name = "videoTitle" >
            </div>
            <div class="form-group">
                <label for="videoDescription">Описание</label>
                <textarea class="form-control" id="videoDescription" rows="3" placeholder="Введите описание видео" name = "videoDescription"></textarea>
            </div>
            <div class="form-group">
                <label for="videoFile">Выберите файл</label>
                <input type="file" class="form-control-file" id="videoFile" name = "videoFile">
            </div>
            <button type="submit" class="btn btn-primary">Загрузить</button>
        </form>
    </div>
</div>

<!-- Bootstrap JavaScript и зависимости -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
