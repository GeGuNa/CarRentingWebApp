<?php 
include 'admin_check.php'; 
include 'includes/header.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id'])) {
    $id = (int)$_POST['booking_id'];
    $status = $_POST['status'];
    $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?")->execute([$status, $id]);
    header("Location: admin_bookings.php?msg=updated");
    exit;
}

$bookings = $pdo->query("SELECT b.*, c.make, c.model, u.name as user_name, u.email as user_email 
                         FROM bookings b 
                         JOIN cars c ON b.car_id = c.id 
                         JOIN users u ON b.user_id = u.id 
                         ORDER BY b.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mx-auto px-6 py-16">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">All Bookings</h1>

    <?php if(isset($_GET['msg'])): ?>
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">Status updated!</div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-4">ID</th>
                    <th class="p-4">Customer</th>
                    <th class="p-4">Car</th>
                    <th class="p-4">Dates</th>
                    <th class="p-4">Total</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($bookings as $b): ?>
                <tr class="border-t">
                    <td class="p-4">#<?php echo $b['id']; ?></td>
                    <td class="p-4">
                        <p class="font-bold"><?php echo htmlspecialchars($b['user_name']); ?></p>
                        <p class="text-xs text-gray-500"><?php echo htmlspecialchars($b['user_email']); ?></p>
                    </td>
                    <td class="p-4"><?php echo htmlspecialchars($b['make'] . ' ' . $b['model']); ?></td>
                    <td class="p-4 text-sm">
                        <?php echo $b['start_date']; ?> <br> to <br> <?php echo $b['end_date']; ?>
                    </td>
                    <td class="p-4 font-bold">$<?php echo number_format($b['total_price'], 2); ?></td>
                    <td class="p-4">
                        <span class="px-2 py-1 rounded text-xs 
                            <?php echo $b['status'] == 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                            <?php echo ucfirst($b['status']); ?>
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <form method="POST" class="inline">
                            <input type="hidden" name="booking_id" value="<?php echo $b['id']; ?>">
                            <select name="status" class="border rounded text-sm p-1" onchange="this.form.submit()">
                                <option value="pending" <?php if($b['status']=='pending') echo 'selected'; ?>>Pending</option>
                                <option value="confirmed" <?php if($b['status']=='confirmed') echo 'selected'; ?>>Confirmed</option>
                                <option value="completed" <?php if($b['status']=='completed') echo 'selected'; ?>>Completed</option>
                                <option value="cancelled" <?php if($b['status']=='cancelled') echo 'selected'; ?>>Cancelled</option>
                            </select>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
