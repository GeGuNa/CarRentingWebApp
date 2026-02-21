<?php 
require 'config/db.php'; 
include 'includes/header.php'; 

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$booking_id = (int)$_GET['id'];

// Fetch Booking Details
$stmt = $pdo->prepare("SELECT b.*, c.make, c.model, c.image, c.type, u.name, u.email FROM bookings b JOIN cars c ON b.car_id = c.id JOIN users u ON b.user_id = u.id WHERE b.id = ? AND b.user_id = ?");
$stmt->execute([$booking_id, $_SESSION['user_id']]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die("Booking not found.");
}

?>

<div class="container mx-auto px-6 py-16">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-lg border-t-4 border-green-500">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Booking Confirmed!</h1>
            <p class="text-gray-500">Your ride is ready. Booking ID: #<?php echo $booking['id']; ?></p>
        </div>

   
        <div class="border-t border-b py-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center gap-4">
                    <img src="<?php echo htmlspecialchars($booking['image']); ?>" class="w-20 h-20 rounded object-cover">
                    <div>
                        <h3 class="font-bold text-lg"><?php echo htmlspecialchars($booking['make'] . ' ' . $booking['model']); ?></h3>
                        <p class="text-sm text-gray-500"><?php echo htmlspecialchars($booking['type']); ?></p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Total Paid</p>
                    <p class="text-2xl font-bold text-blue-600">$<?php echo number_format($booking['total_price'], 2); ?></p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Pick-up Date</p>
                    <p class="font-semibold"><?php echo date('M d, Y', strtotime($booking['start_date'])); ?></p>
                </div>
                <div>
                    <p class="text-gray-500">Drop-off Date</p>
                    <p class="font-semibold"><?php echo date('M d, Y', strtotime($booking['end_date'])); ?></p>
                </div>
                <div>
                    <p class="text-gray-500">Customer</p>
                    <p class="font-semibold"><?php echo htmlspecialchars($booking['name']); ?></p>
                </div>
                <div>
                    <p class="text-gray-500">Status</p>
                    <p class="font-semibold text-green-600"><?php echo ucfirst($booking['status']); ?></p>
                </div>
            </div>
        </div>

        <div class="flex gap-4">
            <a href="dashboard.php" class="flex-1 text-center bg-gray-800 text-white py-3 rounded-lg hover:bg-gray-900 transition">Go to Dashboard</a>
            <button onclick="window.print()" class="flex-1 bg-white border border-gray-300 text-gray-700 py-3 rounded-lg hover:bg-gray-50 transition">Print Invoice</button>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
