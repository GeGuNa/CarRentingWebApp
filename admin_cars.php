<?php 
include 'admin_check.php'; 
include 'includes/header.php'; 

//To delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM cars WHERE id = ?")->execute([$id]);
    header("Location: admin_cars.php?msg=deleted");
    exit;
}

$cars = $pdo->query("SELECT * FROM cars ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mx-auto px-6 py-16">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Manage Cars</h1>
        <a href="admin_add_car.php" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">+ Add New Car</a>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">Action successful!</div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-4">Image</th>
                    <th class="p-4">Car Details</th>
                    <th class="p-4">Type</th>
                    <th class="p-4">Price/Day</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($cars as $car): ?>
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-4">
                        <img src="<?php echo htmlspecialchars($car['image']); ?>" class="w-16 h-16 rounded object-cover">
                    </td>
                    <td class="p-4">
                        <p class="font-bold"><?php echo htmlspecialchars($car['make'] . ' ' . $car['model']); ?></p>
                        <p class="text-sm text-gray-500"><?php echo $car['year']; ?></p>
                    </td>
                    <td class="p-4"><span class="bg-gray-200 px-2 py-1 rounded text-xs"><?php echo htmlspecialchars($car['type']); ?></span></td>
                    <td class="p-4 font-semibold">$<?php echo number_format($car['price_per_day'], 2); ?></td>
                    <td class="p-4">
                        <?php if($car['is_available']): ?>
                            <span class="text-green-600 text-sm font-semibold">Available</span>
                        <?php else: ?>
                            <span class="text-red-600 text-sm font-semibold">Rented</span>
                        <?php endif; ?>
                    </td>
                    <td class="p-4 text-right space-x-2">
                        <a href="admin_edit_car.php?id=<?php echo $car['id']; ?>" class="text-blue-600 hover:underline">Edit</a>
                        <a href="admin_cars.php?delete=<?php echo $car['id']; ?>" onclick="return confirm('Are you sure?')" class="text-red-600 hover:underline">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
