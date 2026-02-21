<?php 
require 'config/db.php'; 
include 'includes/header.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$car_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;


$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->execute([$car_id]);
$car = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$car) {
    die("Car not found.");
}
?>

<div class="container mx-auto px-6 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
  
        <div>
            <img src="<?php echo htmlspecialchars($car['image']); ?>" alt="<?php echo htmlspecialchars($car['model']); ?>" class="w-full rounded-xl shadow-lg mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($car['make'] . ' ' . $car['model']); ?></h1>
            <div class="flex items-center gap-4 mb-4">
                <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded"><?php echo htmlspecialchars($car['type']); ?></span>
                <span class="text-gray-500"><?php echo $car['year']; ?></span>
            </div>
            <p class="text-gray-600 mb-6"><?php echo htmlspecialchars($car['description']); ?></p>
            
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                <h3 class="font-bold text-lg mb-4">Features</h3>
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                    <div class="flex items-center gap-2">✅ Automatic Transmission</div>
                    <div class="flex items-center gap-2">✅ Air Conditioning</div>
                    <div class="flex items-center gap-2">✅ 5 Seats</div>
                    <div class="flex items-center gap-2">✅ Unlimited Miles</div>
                </div>
            </div>
        </div>

    
        <div class="bg-white p-8 rounded-xl shadow-lg h-fit sticky top-24">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Complete Your Booking</h2>
            
            <form action="confirm_booking.php" method="POST" id="bookingForm">
                <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">
                <input type="hidden" name="price_per_day" value="<?php echo $car['price_per_day']; ?>">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Pick-up Date</label>
                    <input type="date" name="start_date" id="start_date" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Drop-off Date</label>
                    <input type="date" name="end_date" id="end_date" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required min="<?php echo date('Y-m-d'); ?>">
                </div>

            
                <div class="border-t pt-4 mb-6">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Price per day</span>
                        <span class="font-semibold">$<span id="display_price"><?php echo number_format($car['price_per_day'], 2); ?></span></span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Duration</span>
                        <span class="font-semibold"><span id="display_days">0</span> Days</span>
                    </div>
                    <div class="flex justify-between text-xl font-bold text-blue-600 mt-4">
                        <span>Total</span>
                        <span>$<span id="display_total">0.00</span></span>
                    </div>
                </div>

                <button type="submit" id="submitBtn" disabled class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed">Confirm & Pay</button>
                <p class="text-xs text-center text-gray-400 mt-4"> Secure Payment Simulation</p>
            </form>
        </div>
    </div>
</div>


<script>
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const pricePerDay = parseFloat(<?php echo $car['price_per_day']; ?>);
    
    const displayDays = document.getElementById('display_days');
    const displayTotal = document.getElementById('display_total');
    const submitBtn = document.getElementById('submitBtn');

    function calculateTotal() {
        const start = new Date(startDateInput.value);
        const end = new Date(endDateInput.value);

        if (start && end && end > start) {
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
            
            const total = diffDays * pricePerDay;

            displayDays.innerText = diffDays;
            displayTotal.innerText = total.toFixed(2);
            submitBtn.disabled = false;
        } else {
            displayDays.innerText = 0;
            displayTotal.innerText = "0.00";
            submitBtn.disabled = true;
        }
    }

    startDateInput.addEventListener('change', () => {
        endDateInput.min = startDateInput.value;
        calculateTotal();
    });
    endDateInput.addEventListener('change', calculateTotal);
</script>

<?php include 'includes/footer.php'; ?>
