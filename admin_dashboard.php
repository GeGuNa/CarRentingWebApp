<?php 
include 'admin_check.php'; 
include 'includes/header.php'; 


$carsCount = $pdo->query("SELECT COUNT(*) FROM cars")->fetchColumn();
$bookingsCount = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$usersCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
$revenue = $pdo->query("SELECT SUM(total_price) FROM bookings WHERE status = 'confirmed'")->fetchColumn() ?? 0;
?>

<div class="container mx-auto px-6 py-16">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
        <span class="bg-purple-100 text-purple-800 px-4 py-2 rounded-full font-semibold">Admin Mode</span>
    </div>


    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <div class="bg-white p-6 rounded-xl shadow border-l-4 border-blue-500">
            <p class="text-gray-500">Total Cars</p>
            <p class="text-3xl font-bold"><?php echo $carsCount; ?></p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow border-l-4 border-green-500">
            <p class="text-gray-500">Total Bookings</p>
            <p class="text-3xl font-bold"><?php echo $bookingsCount; ?></p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow border-l-4 border-yellow-500">
            <p class="text-gray-500">Registered Users</p>
            <p class="text-3xl font-bold"><?php echo $usersCount; ?></p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow border-l-4 border-indigo-500">
            <p class="text-gray-500">Total Revenue</p>
            <p class="text-3xl font-bold">$<?php echo number_format($revenue, 2); ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <a href="admin_cars.php" class="bg-white p-8 rounded-xl shadow hover:shadow-lg transition flex items-center justify-between group">
            <div>
                <h3 class="text-xl font-bold text-gray-800">Manage Cars</h3>
                <p class="text-gray-500">Add, edit, or remove vehicles</p>
            </div>
            <span class="text-blue-600 group-hover:translate-x-2 transition">→</span>
        </a>
        <a href="admin_bookings.php" class="bg-white p-8 rounded-xl shadow hover:shadow-lg transition flex items-center justify-between group">
            <div>
                <h3 class="text-xl font-bold text-gray-800">Manage Bookings</h3>
                <p class="text-gray-500">View and confirm reservations</p>
            </div>
            <span class="text-blue-600 group-hover:translate-x-2 transition">→</span>
        </a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
