<?php
session_start();
$isUserLoggedIn = isset($_SESSION['logged-in']) && $_SESSION['logged-in'] == TRUE;

if (isset($_SESSION['logged-in']) && $_SESSION['logged-in'] == TRUE) {
    require('../database/database-auth-system.php');
    $user_data = $db->Select(
        "SELECT *
            FROM `users`
                WHERE `telegram_id` = :id",
        [
            'id' => $_SESSION['telegram_id']
        ]
    );
    
    $firstName = $user_data[0]['first_name'];
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="">CinemaBooking</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Главная <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../user/profile.php">Профиль</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../ticketsOrder/tickets.php">Забронировать</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="jumbotron">
            <?php if (!$isUserLoggedIn) : ?>
                <h1 class="display-4">Добро пожаловать в <strong>CinemaBooking</strong>!</h1>
                <p class="lead">Надёжное и быстрое бронирование билетов в кино</p>
                <hr class="my-4">
                <p>Для бронирования билетов в кино требуется авторизоваться:</p>
                <a class="btn btn-primary btn-lg" href="../authentication/login.php" role="button">Авторизоваться</a>
            <?php endif; ?>
            <?php if ($isUserLoggedIn) : ?>
                <h3>Здравствуйте, <strong><?php echo $firstName ?></strong>!</h3>
                <p>Рады видеть вас снова! Наш сервис готов предложить вам лучшие варианты, чтобы сделать ваше кинематографическое время незабываемым</p>
                <p>
                    <a class="btn btn-primary btn-lg" href="../ticketsOrder/tickets.php" role="button">Хочу в кино!</a>
                    <a class="btn btn-primary btn-lg" href="../user/profile.php" role="button">Профиль</a>
                </p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
