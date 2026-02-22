<?php 
require 'config/db.php'; 
include 'includes/header.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['amount'])) {
    $amount = floatval($_POST['amount']);
    if ($amount > 0) {

        $pdo->prepare("UPDATE users SET wallet_balance = wallet_balance + ? WHERE id = ?")->execute([$amount, $user_id]);
        $pdo->prepare("INSERT INTO transactions (user_id, amount, type, description) VALUES (?, ?, 'deposit', 'Wallet Deposit')")->execute([$user_id, $amount]);
        $message = "Funds added successfully!";
    }
}


$user = $pdo->prepare("SELECT wallet_balance FROM users WHERE id = ?");
$user->execute([$user_id]);
$balance = $user->fetchColumn();

$transactions = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC");
$transactions->execute([$user_id]);
$history = $transactions->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mx-auto px-6 py-16">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">My Wallet</h1>

    <?php if($message): ?>
        <div class="bg-green-100 text-green-700 p-4 rounded mb-6"><?php echo $message; ?></div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Balance Card -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-8 rounded-xl shadow-lg">
            <p class="text-blue-100 mb-2">Current Balance</p>
            <h2 class="text-4xl font-bold mb-6">$<?php echo number_format($balance, 2); ?></h2>
            
            <form method="POST" class="flex gap-2">
                <input type="number" name="amount" step="0.01" min="1" placeholder="Amount" class="w-full px-3 py-2 rounded text-gray-800 focus:outline-none" required>
                <button type="submit" class="bg-white text-blue-600 font-bold px-4 py-2 rounded hover:bg-gray-100">Add Funds</button>
            </form>
            <p class="text-xs text-blue-200 mt-4">* This is a simulated payment gateway.</p>
        </div>

 
        <div class="lg:col-span-2 bg-white p-8 rounded-xl shadow-lg">
            <h3 class="text-xl font-bold mb-4">Transaction History</h3>
            <?php if (count($history) > 0): ?>
                <table class="w-full text-left">
                    <thead class="border-b">
                        <tr>
                            <th class="py-2">Date</th>
                            <th class="py-2">Description</th>
                            <th class="py-2">Type</th>
                            <th class="py-2 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($history as $t): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 text-sm text-gray-600"><?php echo date('M d, Y', strtotime($t['created_at'])); ?></td>
                            <td class="py-3"><?php echo htmlspecialchars($t['description']); ?></td>
                            <td class="py-3">
                                <span class="px-2 py-1 text-xs rounded 
                                    <?php echo $t['type'] == 'deposit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                    <?php echo ucfirst($t['type']); ?>
                                </span>
                            </td>
                            <td class="py-3 text-right font-bold 
                                <?php echo $t['type'] == 'deposit' ? 'text-green-600' : 'text-red-600'; ?>">
                                <?php echo $t['type'] == 'deposit' ? '+' : '-'; ?>$<?php echo number_format($t['amount'], 2); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-gray-500">No transactions yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
