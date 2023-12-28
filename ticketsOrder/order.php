<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

require('../database/database-auth-system.php');

if (!isset($_SESSION['logged-in'])) {
    header('Location: ../authentication/login.php');
    exit();
}

if (!isset($_GET['movie_id'])) {
    header('Location: tickets.php');
    exit();
}

$selectedMovieId = $_GET['movie_id'];

$movieInfo = $db->Select("SELECT id, movie_name FROM movies WHERE id = :id", [':id' => $selectedMovieId]);

if (empty($movieInfo)) {
    header('Location: tickets.php');
    exit();
}

$selectedMovieName = $movieInfo[0]['movie_name'];

$bookedSeats = $db->Select("SELECT seat_number FROM bookings WHERE movie_id = :movie_id", [':movie_id' => $selectedMovieId]);
$bookedSeatNumbers = array_map(function($seat) {
    return $seat['seat_number'];
}, $bookedSeats);

// $allSeats = ['A1', 'A2', 'A3', 'B1', 'B2', 'B3'];

$seatsData = $db->Select("SELECT seat_number FROM seats");
$allSeats = array_map(function($seat) {
    return $seat['seat_number'];
}, $seatsData);

$availableSeats = array_diff($allSeats, $bookedSeatNumbers);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seatNumber = isset($_POST['seat_number']) ? $_POST['seat_number'] : null;

    if ($seatNumber !== null && in_array($seatNumber, $availableSeats)) {
        $userId = $_SESSION['telegram_id'];
        $reservationTime = date('Y-m-d H:i:s');

        $insertData = [
            'user_id' => $userId,
            'movie_id' => $selectedMovieId,
            'movie_name' => $selectedMovieName,
            'seat_number' => $seatNumber,
            'reservation_time' => $reservationTime,
        ];

        $orderId = $db->Insert("INSERT INTO bookings (" . implode(', ', array_keys($insertData)) . ") VALUES (:" . implode(', :', array_keys($insertData)) . ")", $insertData);

        header("Location: success.php?movie_id=$selectedMovieId");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Купить билет</title>
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
        <a href="tickets.php">Вернуться</a>
        <?php if (!empty($selectedMovieName)): ?>
            <?php if (empty($availableSeats)): ?>
                <h2>Все места на фильм <strong><?php echo $selectedMovieName; ?></strong> забронированы, приносим свои извинения</h2>
            <?php else: ?>
                <h2>Выберите место для фильма "<?php echo $selectedMovieName; ?>"</h2>
                <form id="bookingForm" method="post" action="">
                    <div class="form-group">
                    <select class="form-control" id="seatSelect" name="seat_number" required>
                    <?php foreach ($allSeats as $seat): ?>
                        <option value="<?php echo $seat; ?>" <?php echo in_array($seat, $availableSeats) ? '' : 'disabled'; ?>>
                         <?php echo $seat; ?>
                        </option>
                    <?php endforeach; ?>
                    </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Забронировать билет</button>
                </form>
            <?php endif; ?>
        <?php else: ?>
            <h2>Выберите место</h2>
        <?php endif; ?>
</body>

</html>