<?php

function calculateCarRating($pdo, $car_id) {
    $stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as total FROM reviews WHERE car_id = ?");
    $stmt->execute([$car_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $avg = $result['avg_rating'] ?? 0;
    $total = $result['total'] ?? 0;
    

    $update = $pdo->prepare("UPDATE cars SET average_rating = ?, total_reviews = ? WHERE id = ?");
    $update->execute([$avg, $total, $car_id]);
    
    return ['average' => round($avg, 2), 'total' => $total];
}

function displayStars($rating) {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $stars .= '<span class="text-yellow-400">★</span>';
        } else {
            $stars .= '<span class="text-gray-300">★</span>';
        }
    }
    return $stars;
}

function canReview($pdo, $user_id, $car_id) {
 
    $stmt = $pdo->prepare("
        SELECT b.id FROM bookings b 
        LEFT JOIN reviews r ON b.id = r.booking_id 
        WHERE b.user_id = ? AND b.car_id = ? AND b.status = 'completed' AND r.id IS NULL
    ");
    $stmt->execute([$user_id, $car_id]);
    return $stmt->rowCount() > 0;
}
?>
