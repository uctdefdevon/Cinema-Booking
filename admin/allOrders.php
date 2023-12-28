<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

if (!isset($_SESSION['logged-in'])) {
    header('Location: ../authentication/login.php');
    exit();
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

if ($user_data[0]['is_admin'] != 1) {
    header('Location: ../user/profile.php');
    exit();
}
$booking_history = $db->Select(
    "SELECT * FROM `bookings`"
);

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администрирования</title>
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
                <li class="nav-item">
                    <a class="nav-link" href="../ticketsOrder/tickets.php">Забронировать</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="../admin/panel.php">Панель администрирования</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <p><a href="panel.php">Вернуться к панели администрирования</a></p>
    </div>

    <div class="container">
        <h2>Все заказы</h2>
        <?php if (!empty($booking_history)) : ?>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Пользователь</th>
                        <th scope="col">Фильм</th>
                        <th scope="col">Место</th>
                        <th scope="col">Дата заказа</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($booking_history as $booking) : ?>
                        <tr>
                            <th scope="row"><?php echo $booking['id']; ?></th>
                            <td><?php echo $booking['user_id']; ?></td>
                            <td><?php echo $booking['movie_name']; ?></td>
                            <td><?php echo $booking['seat_number']; ?></td>
                            <td><?php echo $booking['reservation_time']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>У вас пока нет заказов.</p>
        <?php endif; ?>
    </div>

</body>

</html>
