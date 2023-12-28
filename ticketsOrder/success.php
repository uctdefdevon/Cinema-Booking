<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

require('../database/database-auth-system.php');

if (!isset($_SESSION['logged-in'])) {
    die(header('Location: ../authentication/login.php'));
}

$selectedMovieId = isset($_GET['movie_id']) ? $_GET['movie_id'] : die(header('Location: error.php'));

$userIdInSession = $_SESSION['telegram_id'];

if ($selectedMovieId !== null) {
    $bookingQuery = $db->Select("SELECT id, movie_id, user_id FROM bookings WHERE user_id = :user_id AND movie_id = :movie_id", [
        ':user_id' => $userIdInSession,
        ':movie_id' => $selectedMovieId
    ]);

    if (count($bookingQuery) > 0) {
        $orderId = $bookingQuery[0]['id'];

        $movieQuery = $db->Select("SELECT movie_name FROM movies WHERE id = :id", [':id' => $selectedMovieId]);
        $selectedMovieName = $movieQuery[0]['movie_name'];
        
        $seatNumberQuery = $db->Select("SELECT seat_number FROM bookings WHERE id = :order_id", [
            ':order_id' => $orderId
        ]);

        if (count($seatNumberQuery) > 0) {
            $seatNumber = $seatNumberQuery[0]['seat_number'];
        } else {
            die(header('Location: error.php'));
        }
    } else {
        die(header('Location: error.php'));
    }
} else {
    die(header('Location: error.php'));
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Успешное бронирование</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">   
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="../index.php">CinemaBooking</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="../index.php">Главная</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../user/profile.php">Профиль</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="tickets.php">Забронировать <span class="sr-only">(current)</span></a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <h2>Успешное бронирование</h2>
    <p>Вы успешно забронировали билет на фильм: <strong><?php echo $selectedMovieName; ?></strong></p>
    <p>Необходимая информация о вашем билете доступна в <a href="../user/profile.php">истории заказов</a>.</p>
    <p>Спасибо за ваш выбор!</p>

    <a href="../user/profile.php" class="btn btn-primary">К заказам</a>
</div>

</body>

</html>
