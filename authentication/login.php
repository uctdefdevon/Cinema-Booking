<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

if (isset($_SESSION['logged-in']) && $_SESSION['logged-in'] == TRUE) {
    die(header('Location: ../user/profile.php'));
}

require('../database/config.php');

define('BOT_USERNAME', $BOT_USERNAME);
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
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
                    <a class="nav-link" href="login.php">Авторизация <span class="sr-only">(current)</span></a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-body mx-auto text-center">
                        <h2 class="card-title">Авторизация</h2>
                        <p>Для авторизации, пожалуйста, войдите через Telegram</p>
                        <script async src="https://telegram.org/js/telegram-widget.js?22"
                            data-telegram-login="<?= BOT_USERNAME ?>" data-size="large" data-userpic="false" data-radius="20"
                            data-auth-url="logic/auth.php">
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            window.TelegramAuthCallback = function (user) {
                var data = {
                    first_name: user.first_name,
                    last_name: user.last_name,
                    telegram_id: user.id,
                    telegram_username: user.username,
                    profile_picture: user.photo_url,
                    auth_date: user.auth_date
                };

                $.ajax({
                    url: 'auth.php',
                    type: 'POST',
                    data: data,
                    success: function (response) {
                        if (response === "success") {
                            window.location.href = '../user/profile.php';
                        } else {
                            $('#errorContainer').show().text(response);
                        }
                    },
                    error: function () {
                        $('#errorContainer').show().text('Ошибка выполнения запроса.');
                    }
                });
            };

            $('#loginForm').on('submit', function (e) {
                e.preventDefault();
                var login = $('#login').val();
                var password = $('#password').val();

                $.ajax({
                    url: 'auth.php',
                    type: 'POST',
                    data: {
                        login: login,
                        password: password
                    },
                    success: function (response) {
                        if (response === "success") {
                            window.location.href = '../user/profile.php';
                        } else {
                            $('#errorContainer').show().text(response);
                        }
                    },
                    error: function () {
                        $('#errorContainer').show().text('Ошибка выполнения запроса.');
                    }
                });
            });
        });
    </script>

</body>

</html>