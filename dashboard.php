<?php 
require 'config/db.php'; 
include 'includes/header.php'; 


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);


$bookingStmt = $pdo->prepare("SELECT b.*, c.make, c.model, c.image FROM bookings b JOIN cars c ON b.car_id = c.id WHERE b.user_id = ? ORDER BY b.created_at DESC");
$bookingStmt->execute([$user_id]);
$bookings = $bookingStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mx-auto px-6 py-16">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">My Dashboard</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
       
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <div class="flex items-center space-x-4 mb-6">
                <div class="h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-2xl font-bold">
                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                </div>
                <div>
                    <h2 class="text-xl font-bold"><?php echo htmlspecialchars($user['name']); ?></h2>
                    <p class="text-gray-500"><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
            </div>
            <div class="border-t pt-4 space-y-3">
                <a href="settings.php" class="block text-gray-600 hover:text-blue-600">⚙️ Account Settings</a>
                <a href="wallet.php" class="block text-gray-600 hover:text-blue-600">💰 Wallet & Payments</a>
                <a href="refer.php" class="block text-gray-600 hover:text-blue-600">🎁 Refer & Earn</a>
            </div>
        </div>

      
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-lg">
            <h3 class="text-xl font-bold mb-4">Rental History</h3>
            <?php if (count($bookings) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b">
                                <th class="py-2">Car</th>
                                <th class="py-2">Dates</th>
                                <th class="py-2">Total</th>
                                <th class="py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($bookings as $booking): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 flex items-center gap-2">
                                    <img src="<?php echo htmlspecialchars($booking['image']); ?>" class="w-10 h-10 rounded object-cover">
                                    <span class="font-semibold"><?php echo htmlspecialchars($booking['make'] . ' ' . $booking['model']); ?></span>
                                </td>
                                <td class="py-3 text-sm text-gray-600">
                                    <?php echo $booking['start_date']; ?> to <?php echo $booking['end_date']; ?>
                                </td>
                                <td class="py-3 font-bold">$<?php echo number_format($booking['total_price'], 2); ?></td>
                                <td class="py-3">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        <?php echo $booking['status'] == 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                        <?php echo ucfirst($booking['status']); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-8">No rentals found. <a href="index.php" class="text-blue-600 hover:underline">Rent a car now!</a></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
