<?php
session_start();
$userId = $_SESSION ['user_id'];

//Подключение к БД
$mysql = new mysqli("Localhost", "root", "", "youtube");
$mysql->query("SET NAMES 'utf8'");

if ($mysql->connect_error){

    echo 'Error number:'.$mysql->connect_errno.'<br>';
    echo 'Error: '.$mysql->connect_error;
}

// Извлечение нужных данных из БД
$result = $mysql->query("SELECT `id`, `title`,`description`,`url` FROM `videos`");

// Инициализация массива для хранения id видео
$_SESSION['video_ids'] = [];

?>


<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Главная - Видео Портал</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .video-card {
    margin-bottom: 20px;
    }
    .video-thumbnail {
    width: 100%;
    height: 180px;
      object-fit: cover;
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
      <li class="nav-item active">
        <a class="nav-link" href="#">Главная</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="upload.php">Загрузить Видео</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="registration.php">Вход/Регистрация</a>
      </li>
    </ul>
  </div>
</nav>

<!-- Контентная часть -->
<div class="container mt-4">
  <div class="row">
    <!-- Плейсхолдеры для видео -->
    <div class="col-md-4">
<?php while ($row = $result->fetch_assoc()): ?>
      <div class="card video-card">
<img class="card-img-top video-thumbnail" src="placeholder.png" alt="Видео">

        <div class="card-body">
          <h5 class="card-title"><?php echo $row['title']; ?></h5>
          <p class="card-text"><?php echo $row['description']; ?>.</p>
          <a href="watch.php?url=<?php echo $row['url'];?>" class="btn btn-primary">Смотреть</a>
        </div>
      </div>
    </div>
    <!-- Добавьте дополнительные блоки для каждого видео -->
      <?php
      // Добавление id видео в массив в сессии
      $_SESSION['video_ids'][] = $row['id'];
      ?>
      <?php
      endwhile; ?>
  </div>
</div>

<!-- Bootstrap JavaScript и зависимости -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>

