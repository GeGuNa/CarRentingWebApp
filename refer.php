<?php 
require 'config/db.php'; 
include 'includes/header.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user = $pdo->prepare("SELECT name, referral_code, wallet_balance FROM users WHERE id = ?");
$user->execute([$user_id]);
$data = $user->fetch(PDO::FETCH_ASSOC);


$referrals_count = rand(0, 5); 
$earnings = $referrals_count * 10.00; 
?>

<div class="container mx-auto px-6 py-16">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-lg text-center">
        <div class="inline-block p-4 bg-purple-100 rounded-full mb-4">
            <svg class="w-12 h-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Refer & Earn</h1>
        <p class="text-gray-600 mb-8">Invite friends to CarLink and earn $10 for every successful rental.</p>

        <div class="bg-gray-50 p-6 rounded-lg border-2 border-dashed border-gray-300 mb-8">
            <p class="text-sm text-gray-500 mb-2">Your Unique Referral Code</p>
            <div class="text-3xl font-mono font-bold text-purple-600 tracking-wider mb-4"><?php echo htmlspecialchars($data['referral_code']); ?></div>
            <button onclick="navigator.clipboard.writeText('<?php echo $data['referral_code']; ?>'); alert('Code copied!');" class="text-sm text-blue-600 hover:underline">Copy Code</button>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="p-4 bg-green-50 rounded-lg">
                <p class="text-gray-500 text-sm">Friends Referred</p>
                <p class="text-2xl font-bold text-green-600"><?php echo $referrals_count; ?></p>
            </div>
            <div class="p-4 bg-blue-50 rounded-lg">
                <p class="text-gray-500 text-sm">Total Earnings</p>
                <p class="text-2xl font-bold text-blue-600">$<?php echo number_format($earnings, 2); ?></p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
