<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

if (!isset($_SESSION['logged-in'])) {
    die(header('Location: ../authentication/login.php'));
}

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
$lastName = $user_data[0]['last_name'];
$profilePicture = $user_data[0]['profile_picture'];
$telegramID = $user_data[0]['telegram_id'];
$telegramUsername = $user_data[0]['telegram_username'];
$userID = $user_data[0]['id'];

$booking_history = $db->Select(
    "SELECT * FROM `bookings` WHERE `user_id` = :user_id",
    ['user_id' => $telegramID]
);

$isAdmin = ($user_data[0]['is_admin'] == 1);

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль</title>
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
                <li class="nav-item active">
                    <a class="nav-link" href="profile.php">Профиль <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../ticketsOrder/tickets.php">Забронировать</a>
                </li>
                <?php if ($isAdmin) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../admin/panel.php">Панель администрирования</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="jumbotron">
            <h1 class="display-4">Это ваш профиль, <strong><?php echo $firstName ?></strong>!</h1>
            <hr class="my-4">
            <a class="btn btn-primary btn-lg" href="../ticketsOrder/tickets.php" role="button">Хочу в кино!</a>
        </div>
    </div>

    <div class="container">
        <h2>История заказов</h2>
        <?php if (!empty($booking_history)) : ?>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Фильм</th>
                        <th scope="col">Место</th>
                        <th scope="col">Дата заказа</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($booking_history as $booking) : ?>
                        <tr>
                            <td><?php echo $booking['movie_name']; ?></td>
                            <td><?php echo $booking['seat_number']; ?></td>
                            <td><?php echo $booking['reservation_time']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p><strong>У вас пока нет заказов</strong></p>
        <?php endif; ?>
    </div>

    <div class="container">
        <p><a href="../authentication/logic/logout.php" class="btn btn-primary">Выйти из аккаунта</a></p>
    </div>

</body>

</html>