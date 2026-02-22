<?php 
require 'config/db.php'; 
require 'includes/functions.php';
include 'includes/header.php'; 

$car_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;


$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->execute([$car_id]);
$car = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$car) {
    die("Car not found.");
}


$rating = calculateCarRating($pdo, $car_id);


$reviewStmt = $pdo->prepare("
    SELECT r.*, u.name as user_name, u.user_rating 
    FROM reviews r 
    JOIN users u ON r.user_id = u.id 
    WHERE r.car_id = ? 
    ORDER BY r.created_at DESC
");
$reviewStmt->execute([$car_id]);
$reviews = $reviewStmt->fetchAll(PDO::FETCH_ASSOC);


$can_review = false;
$booking_id_for_review = null;
if (isset($_SESSION['user_id'])) {
    $checkStmt = $pdo->prepare("
        SELECT b.id FROM bookings b 
        LEFT JOIN reviews r ON b.id = r.booking_id 
        WHERE b.user_id = ? AND b.car_id = ? AND b.status = 'completed' AND r.id IS NULL
        LIMIT 1
    ");
    $checkStmt->execute([$_SESSION['user_id'], $car_id]);
    $booking = $checkStmt->fetch(PDO::FETCH_ASSOC);
    if ($booking) {
        $can_review = true;
        $booking_id_for_review = $booking['id'];
    }
}
?>

<div class="container mx-auto px-6 py-16">
   
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
        <div>
            <img src="<?php echo htmlspecialchars($car['image']); ?>" class="w-full rounded-xl shadow-lg mb-6">
        </div>
        <div>
            <h1 class="text-4xl font-bold text-gray-800 mb-4"><?php echo htmlspecialchars($car['make'] . ' ' . $car['model']); ?></h1>
            
            <div class="flex items-center gap-4 mb-6">
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded"><?php echo htmlspecialchars($car['type']); ?></span>
                <span class="text-gray-500"><?php echo $car['year']; ?></span>
            </div>
            
         
            <div class="bg-gray-50 p-6 rounded-xl mb-6">
                <div class="flex items-center gap-4">
                    <div class="text-5xl font-bold text-blue-600"><?php echo $rating['average']; ?></div>
                    <div>
                        <div class="flex text-2xl">
                            <?php echo displayStars(round($rating['average'])); ?>
                        </div>
                        <p class="text-gray-600"><?php echo $rating['total']; ?> reviews</p>
                    </div>
                </div>
            </div>
            
            <p class="text-gray-600 mb-6"><?php echo htmlspecialchars($car['description']); ?></p>
            
            <div class="flex items-center gap-6 mb-8">
                <span class="text-3xl font-bold text-blue-600">$<?php echo number_format($car['price_per_day'], 2); ?> <span class="text-lg text-gray-500">/day</span></span>
                <?php if($car['is_available']): ?>
                    <a href="book.php?id=<?php echo $car['id']; ?>" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition">Book Now</a>
                <?php else: ?>
                    <span class="bg-red-100 text-red-600 px-6 py-3 rounded-lg font-semibold">Currently Unavailable</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

  
    <div class="border-t pt-12">
        <h2 class="text-3xl font-bold text-gray-800 mb-8">Customer Reviews</h2>
        
        <?php if($can_review): ?>
            <div class="bg-green-50 border border-green-200 p-6 rounded-xl mb-8">
                <h3 class="font-bold text-green-800 mb-2">You can review this car!</h3>
                <p class="text-green-700 mb-4">You've completed a rental with this car. Share your experience.</p>
                <button onclick="document.getElementById('reviewForm').classList.remove('hidden')" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">Write a Review</button>
                
               
                <form id="reviewForm" method="POST" action="submit_review.php" class="hidden mt-6 pt-6 border-t border-green-200">
                    <input type="hidden" name="booking_id" value="<?php echo $booking_id_for_review; ?>">
                    <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Rating</label>
                        <div class="flex gap-2 text-3xl" id="starRating">
                            <?php for($i=1; $i<=5; $i++): ?>
                                <span class="cursor-pointer text-gray-300 hover:text-yellow-400 star" data-rating="<?php echo $i; ?>">★</span>
                            <?php endfor; ?>
                            <input type="hidden" name="rating" id="ratingInput" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Your Review</label>
                        <textarea name="review_text" rows="4" class="w-full border rounded-lg p-3 focus:outline-none focus:border-green-500" placeholder="Share your experience with this car..." required></textarea>
                    </div>
                    
                    <div class="flex gap-4">
                        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">Submit Review</button>
                        <button type="button" onclick="document.getElementById('reviewForm').classList.add('hidden')" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">Cancel</button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
        
        
        <div class="space-y-6">
            <?php if(count($reviews) > 0): ?>
                <?php foreach($reviews as $review): ?>
                    <div class="bg-white p-6 rounded-xl shadow border">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="font-bold text-gray-800"><?php echo htmlspecialchars($review['user_name']); ?></p>
                                <p class="text-sm text-gray-500"><?php echo date('M d, Y', strtotime($review['created_at'])); ?></p>
                            </div>
                            <div class="flex">
                                <?php echo displayStars($review['rating']); ?>
                            </div>
                        </div>
                        <p class="text-gray-600"><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500 text-center py-8">No reviews yet. Be the first to review this car!</p>
            <?php endif; ?>
        </div>
    </div>
</div>


<script>
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('ratingInput');
    
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.getAttribute('data-rating');
            ratingInput.value = rating;
            
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.classList.remove('text-gray-300');
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                    s.classList.add('text-gray-300');
                }
            });
        });
        
        star.addEventListener('mouseover', function() {
            const rating = this.getAttribute('data-rating');
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                }
            });
        });
    });
    
    document.getElementById('starRating').addEventListener('mouseleave', function() {
        const currentRating = ratingInput.value;
        stars.forEach((s, index) => {
            if (index < currentRating) {
                s.classList.add('text-yellow-400');
                s.classList.remove('text-gray-300');
            } else {
                s.classList.remove('text-yellow-400');
                s.classList.add('text-gray-300');
            }
        });
    });
</script>

<?php include 'includes/footer.php'; ?>
