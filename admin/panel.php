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

$firstName = $user_data[0]['first_name'];

$booking_history = $db->Select(
    "SELECT * FROM `bookings`"
);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_movie'])) {
    $movieId = $_POST['movie_id'];

    $db->Insert(
        "DELETE FROM `movies` WHERE `id` = :movie_id",
        ['movie_id' => $movieId]
    );

    header('Location: panel.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_movie'])) {
    $movieName = $_POST['movie_name'];

    $db->Insert(
        "INSERT INTO `movies` (`movie_name`) VALUES (:movie_name)",
        ['movie_name' => $movieName]
    );

    header('Location: panel.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_movie'])) {
    $movieId = $_POST['movie_id'];
    $newMovieName = $_POST['edit_movie_name'];

    $db->Update(
        "UPDATE `movies` SET `movie_name` = :new_movie_name WHERE `id` = :movie_id",
        ['new_movie_name' => $newMovieName, 'movie_id' => $movieId]
    );

    header('Location: panel.php');
    exit();
}

$movies = $db->Select("SELECT * FROM `movies`");

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
        <div class="jumbotron">
            <h1>Добро пожаловать в панель администрирования, <?php echo $firstName; ?>!</h1>
                    <p>
            <a href="allOrders.php" class="btn btn-primary">Заказы</a>
            <a href="allUsers.php" class="btn btn-primary">Пользователи</a>
            <a href="allSeats.php" class="btn btn-primary">Места</a>
        </p>
        </div>
    </div>

    <div class="container mt-4">
        <h2>Добавить фильм в прокат</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="movie_name">Название фильма:</label>
                <input type="text" class="form-control" id="movie_name" name="movie_name" required>
            </div>
            <button type="submit" class="btn btn-primary" name="add_movie">Добавить фильм</button>
        </form>
    </div>

    <div class="container mt-4">
        <h2>Список фильмов</h2>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Название фильма</th>
                    <th scope="col">Редактировать</th>
                    <th scope="col">Удалить</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($movies as $index => $movie) : ?>
                    <tr>
                        <th scope="row"><?php echo $index + 1; ?></th>
                        <td><?php echo $movie['movie_name']; ?></td>
                        <td>
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editModal<?php echo $movie['id']; ?>">Редактировать</button>
                        </td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="movie_id" value="<?php echo $movie['id']; ?>">
                                <button type="submit" class="btn btn-danger" name="delete_movie">Удалить</button>
                            </form>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal<?php echo $movie['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Редактировать фильм</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="post" action="">
                                        <div class="form-group">
                                            <label for="edit_movie_name">Новое название фильма:</label>
                                            <input type="text" class="form-control" id="edit_movie_name" name="edit_movie_name" value="<?php echo $movie['movie_name']; ?>" required>
                                        </div>
                                        <input type="hidden" name="movie_id" value="<?php echo $movie['id']; ?>">
                                        <button type="submit" class="btn btn-primary" name="edit_movie">Сохранить изменения</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>