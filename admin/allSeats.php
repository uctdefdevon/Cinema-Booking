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
$seats = $db->Select(
    "SELECT * FROM `seats`"
);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_seat'])) {
    $seatNumber = strtoupper($_POST['seat_number']);

    $existingSeat = $db->Select("SELECT * FROM `seats` WHERE `seat_number` = :seat_number", ['seat_number' => $seatNumber]);

    if (empty($existingSeat)) {
        $db->Insert("INSERT INTO `seats` (`seat_number`) VALUES (:seat_number)", ['seat_number' => $seatNumber]);
        header('Location: allSeats.php');
        exit();
    } else {
        $message = "Такое место уже существует";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_seat'])) {
    $id = $_POST['id'];

    $db->Insert(
        "DELETE FROM `seats` WHERE `id` = :id",
        ['id' => $id]
    );

    header('Location: allSeats.php');
    exit();
}

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

    <div class="container mt-4">
        <h2>Добавить место</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="seat_number">Номер места:</label>
                <input type="text" class="form-control" id="seat_number" name="seat_number" pattern="[A-Za-z0-9]+" title="Пожалуйста, используйте только буквы латинского алфавита и цифры" required>
            </div>
            <button type="submit" class="btn btn-primary" name="add_seat">Добавить место</button>
            <p>
                <?php
                if(isset($message)) {
                    echo $message;
                };
                ?>
            </p>
        </form>
    </div>

    <div class="container">
        <h2>Все места в зале</h2>
        <?php if (!empty($seats)) : ?>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Место</th>
                        <th scope="col">Удаление</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($seats as $seat) : ?>
                        <tr>
                            <th scope="row"><?php echo $seat['id']; ?></th>
                            <td><?php echo $seat['seat_number']; ?></td>
                            <td>
                            <form method="post" action="">
                                <input type="hidden" name="id" value="<?php echo $seat['id']; ?>">
                                <button type="submit" class="btn btn-danger" name="delete_seat">Удалить</button>
                            </form>
                        </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>В зале не указаны места</p>
        <?php endif; ?>
    </div>

</body>

</html>
