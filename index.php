<?php 
require 'config/db.php'; 
include 'includes/header.php'; 

$stmt = $pdo->query("SELECT * FROM cars WHERE is_available = 1");
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<header class="relative bg-blue-600 h-96 flex items-center justify-center text-center px-4">
    <div class="absolute inset-0 bg-black opacity-50"></div> 
    <div class="relative z-10 text-white">
        <h1 class="text-5xl font-bold mb-4">Find Your Perfect Drive</h1>
        <p class="text-xl mb-8">Premium cars. Affordable prices. Unlimited miles.</p>
        <a href="#available-cars" class="bg-white text-blue-600 px-8 py-3 rounded-full font-bold hover:bg-gray-100 transition">Browse Cars</a>
    </div>
</header>


<section id="available-cars" class="container mx-auto px-6 py-16">
    <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Available Cars</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach($cars as $car): ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 transform hover:-translate-y-1">
                <img src="<?php echo htmlspecialchars($car['image']); ?>" alt="<?php echo htmlspecialchars($car['model']); ?>" class="w-full h-48 object-cover">
                
                <div class="p-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded"><?php echo htmlspecialchars($car['type']); ?></span>
                        <span class="text-gray-500 text-sm"><?php echo $car['year']; ?></span>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($car['make'] . ' ' . $car['model']); ?></h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2"><?php echo htmlspecialchars($car['description']); ?></p>
                    
                    <div class="flex justify-between items-center border-t pt-4">
                        <span class="text-2xl font-bold text-blue-600">$<?php echo number_format($car['price_per_day'], 2); ?> <span class="text-sm text-gray-500 font-normal">/day</span></span>
                        <a href="book.php?id=<?php echo $car['id']; ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Rent Now</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
