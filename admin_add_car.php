<?php 
include 'admin_check.php'; 
include 'includes/header.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $type = $_POST['type'];
    $price = $_POST['price'];
    $image = $_POST['image']; 
    $desc = $_POST['description'];

    $stmt = $pdo->prepare("INSERT INTO cars (make, model, year, type, price_per_day, image, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$make, $model, $year, $type, $price, $image, $desc]);

    header("Location: admin_cars.php?msg=added");
    exit();
}
?>

<div class="container mx-auto px-6 py-16">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold mb-6">Add New Car</h2>
        <form method="POST">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-bold mb-2">Make</label>
                    <input type="text" name="make" class="w-full border p-2 rounded" required placeholder="e.g. Toyota">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2">Model</label>
                    <input type="text" name="model" class="w-full border p-2 rounded" required placeholder="e.g. Camry">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-bold mb-2">Year</label>
                    <input type="number" name="year" class="w-full border p-2 rounded" required placeholder="2023">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2">Type</label>
                    <select name="type" class="w-full border p-2 rounded">
                        <option>Sedan</option>
                        <option>SUV</option>
                        <option>Sports</option>
                        <option>Luxury</option>
                        <option>Taxi</option>
                    </select>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-bold mb-2">Price Per Day</label>
                <input type="number" step="0.01" name="price" class="w-full border p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-bold mb-2">Image URL</label>
                <input type="url" name="image" class="w-full border p-2 rounded" required placeholder="https://...">
                <p class="text-xs text-gray-500">Use a direct image link.</p>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-bold mb-2">Description</label>
                <textarea name="description" class="w-full border p-2 rounded" rows="4"></textarea>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded hover:bg-blue-700">Add Car</button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
