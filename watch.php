<?php
session_start();
$userId = $_SESSION ['user_id'];
$url = $_GET['url'];
$videoPath = basename($url);
$videoIds= $_SESSION['video_ids'];


//Подключение к БД
$mysql = new mysqli("Localhost", "root", "", "youtube");
$mysql->query("SET NAMES 'utf8'");

if ($mysql->connect_error){

    echo 'Error number:'.$mysql->connect_errno.'<br>';
    echo 'Error: '.$mysql->connect_error;
}

$result = $mysql->query("SELECT `id`,`title`,`description`,`url` FROM `videos`");

if ($result->num_rows>0){
while ($row=$result->fetch_assoc()) {
    if ($_GET['url']== $row ['url']){
        $videoTitle = $row ['title'];
        $videoDesc = $row['description'];
        $videoId = $videoIds[$row['id']-1];

    }

}
}


// Добавление комментариев в БД
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Обработка комментария
    if (isset($_POST['comment'])) {
        $comment = $_POST['comment'];
        // Добавление в БД
        $mysql->query("INSERT INTO `comments`(`text`,`video_id`,`user_id`) VALUES ('$comment','$videoId','$userId')");
        $result = $mysql->query("SELECT `text`,`user_id` FROM `comments`");
        // Перенаправление пользователя после успешной отправки формы
        header("Location: ".$_SERVER['PHP_SELF']."?url=".$url);
        exit;
    }
    // Обработка положительных оценок
    if (isset($_POST['voteUp'])) {
        $upGrades = $_POST['voteUp'];
        // Добавление в БД
        $mysql->query("INSERT INTO `grade`(`grade`,`video_id`,`user_id`) VALUES ('$upGrades','$videoId','$userId')");
        // Перенаправление пользователя после успешной отправки формы
        header("Location: ".$_SERVER['PHP_SELF']."?url=".$url);
        exit;

    }

    // Обработка отрицательных оценок
    if (isset($_POST['voteDown'])) {
        $dowGrades = $_POST['voteDown'];
        // Добавление в БД
        $mysql->query("INSERT INTO `grade`(`grade`,`video_id`,`user_id`) VALUES ('$dowGrades','$videoId','$userId')");
        // Перенаправление пользователя после успешной отправки формы
        header("Location: ".$_SERVER['PHP_SELF']."?url=".$url);
        exit;

    }
}
//Подсчёт оценок под конкретным видео
$countUp = 0;
$countDown = 0;
$resultCount = $mysql->query ("SELECT grade, COUNT(grade) FROM `grade` WHERE video_id = '$videoId'  GROUP BY grade");

while ($row = $resultCount->fetch_assoc()) {
    if ($row['grade'] == 'down') {
        $countDown = $row['COUNT(grade)'];
    } elseif ($row['grade'] == 'up') {
        $countUp = $row['COUNT(grade)'];
    }
}




?>





<!DOCTYPE html>
<html lang="ru" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Просмотр Видео - Видео Портал</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .video-container {
            max-width: 800px;
            margin: 20px auto;
        }
        .comments-section {
            margin-top: 40px;
        }
        .comment {
            margin-bottom: 20px;
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
                <a class="nav-link" href="video.php">Главная</a>
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

<!-- Видео и информация о видео -->
<div class="video-container">
    <h2><?php echo $videoTitle;?> </h2>
    <video width="100%" controls>
        <source src="<?php echo "uploadVideo"."\\". $videoPath;?>" type="video/mp4">
        Ваш браузер не поддерживает видео.
    </video>
    <p><?php echo $videoDesc;?></p>
    <!-- Оценки -->
    <form action="" method="post">
        <p>
            <button type="submit" name="voteUp" value="up">👍</button><span id="upvotes" "><?php echo  $countUp?></span>
            <span class="ml-4"></span>
            <button type="submit" name="voteDown" value="down" >👎</button><span id="downvotes""><?php echo  $countDown?></span>
        </p>
    </form>
<!--    <p>-->
<!--        <a href="#" class="mr-2"> 👍</a><span id="upvotes"  >0</span>-->
<!--        <span class="ml-4"></span>-->
<!--        <a href="#" class="mr-2" >👎</a><span id="downvotes">0</span>-->
<!--    </p>-->

</div>

<!-- Секция комментариев -->
<div class="comments-section container">
    <h3>Комментарии</h3>
    <!-- Форма для добавления комментария -->
    <form class="mb-3" action="" method="post">
        <div class="form-group">
            <textarea class="form-control" rows="3" name="comment"   placeholder="Добавьте комментарий"> </textarea>
        </div>
        <button type="submit" class="btn btn-primary">Комментировать</button>
    </form>
    <!-- Список комментариев -->
    <?php
    // Получение всех комментариев из базы данных для конкретного видео
    $resultAllComments = $mysql->query("SELECT comments.text, users.email FROM comments INNER JOIN users ON users.id = comments.user_id WHERE comments.video_id = '$videoId'");

    while ($row = $resultAllComments->fetch_assoc()) {
        $person = $row['email'];
        $comment = $row['text'];
        // Отображение каждого комментария
        echo "<div class='comment'>";
        echo "<p><strong>$person:</strong> $comment</p>";
        echo "</div>";
    }
    $mysql->close();
    ?>
    </div>
    <!-- Дополнительные комментарии -->
</div>
</body>
</html>
