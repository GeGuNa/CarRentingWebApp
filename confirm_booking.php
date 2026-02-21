<?php 
require 'config/db.php'; 


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $car_id = (int)$_POST['car_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];


    if (empty($start_date) || empty($end_date)) {
        die("Invalid dates.");
    }

    $start = new DateTime($start_date);
    $end = new DateTime($end_date);

    if ($end <= $start) {
        die("End date must be after start date.");
    }


    $interval = $start->diff($end);
    $days = $interval->days;

  )
    $stmt = $pdo->prepare("SELECT price_per_day FROM cars WHERE id = ?");
    $stmt->execute([$car_id]);
    $car = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$car) {
        die("Car not found.");
    }

    $total_price = $days * $car['price_per_day'];

 
    $insertStmt = $pdo->prepare("INSERT INTO bookings (user_id, car_id, start_date, end_date, total_price, status) VALUES (?, ?, ?, ?, ?, 'confirmed')");
    
    if ($insertStmt->execute([$user_id, $car_id, $start_date, $end_date, $total_price])) {
        $booking_id = $pdo->lastInsertId();
     
        header("Location: booking_success.php?id=" . $booking_id);
        exit();
    } else {
        die("Booking failed. Please try again.");
    }
} else {
    header("Location: index.php");
    exit();
}


?>
