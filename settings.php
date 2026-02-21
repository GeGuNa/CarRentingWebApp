<?php 
require 'config/db.php'; 
include 'includes/header.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';
$error = '';


$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_profile'])) {
        $name = trim($_POST['name']);
        $phone = trim($_POST['phone']);
        
        $updateStmt = $pdo->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
        if ($updateStmt->execute([$name, $phone, $user_id])) {
            $_SESSION['user_name'] = $name;
            $message = "Profile updated successfully.";
            $stmt->execute([$user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    } elseif (isset($_POST['change_password'])) {
        $current_pass = $_POST['current_password'];
        $new_pass = $_POST['new_password'];
        
        if (password_verify($current_pass, $user['password'])) {
            $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
            $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            if ($updateStmt->execute([$hashed, $user_id])) {
                $message = "Password changed successfully.";
            }
        } else {
            $error = "Current password is incorrect.";
        }
    }
}
?>

<div class="container mx-auto px-6 py-16">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Account Settings</h1>

    <?php if($message): ?>
        <div class="bg-green-100 text-green-700 p-4 rounded mb-6"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if($error): ?>
        <div class="bg-red-100 text-red-700 p-4 rounded mb-6"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white p-8 rounded-xl shadow-lg">
            <h2 class="text-xl font-bold mb-6">Profile Information</h2>
            <form method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Full Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email (Read Only)</label>
                    <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="w-full px-3 py-2 border rounded-lg bg-gray-100" disabled>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Phone Number</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <button type="submit" name="update_profile" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">Update Profile</button>
            </form>
        </div>

       
        <div class="bg-white p-8 rounded-xl shadow-lg">
            <h2 class="text-xl font-bold mb-6">Change Password</h2>
            <form method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Current Password</label>
                    <input type="password" name="current_password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">New Password</label>
                    <input type="password" name="new_password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
                </div>
                <button type="submit" name="change_password" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-900 transition">Change Password</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
