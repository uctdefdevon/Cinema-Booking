<?php
session_start();
require('../database/database-auth-system.php');
error_reporting(E_ALL);
ini_set('display_errors', '1');

$data = $db->Select("SELECT id, movie_name FROM movies");
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Забронировать билеты</title>
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

    <div class="container mt-5">
        <h2>Выбор билетов</h2>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Фильм</th>
                    <th scope="col">Забронировать</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if ($data) {
                    foreach ($data as $row) {
                        echo "<tr>";
                        echo "<td>" . $row['movie_name'] . "</td>";
                        echo "<td><a href='order.php?movie_id=" . $row['id'] . "' class='btn btn-primary'>Забронировать</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='1'>Фильмы не найдены</td></tr>";
                }
            ?>
            </tbody>
        </table>
    </div>

</body>
</html>