<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ошибка</title>
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
        <h2>Произошла ошибка</h2>
        <p>При обработке вашего запроса произошла ошибка. Возможно, вы пытались <strong>нас обмануть</strong>. Больше так <strong>не делайте</strong>.</p>
        <p><a href="../index.php" class="btn btn-primary">На главную</a> <a href="../user/profile.php" class="btn btn-primary">Профиль</a> <a href="tickets.php" class="btn btn-primary">К заказам</a></p>
    </div>

</body>

</html>
