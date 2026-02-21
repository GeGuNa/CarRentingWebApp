<?php 
require 'config/db.php'; 
include 'includes/header.php'; 

$message = '';
$error = '';
$reset_link = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $error = "Email is required.";
    } else {
        
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
           
            $token = bin2hex(random_bytes(50));
            $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

          
            $updateStmt = $pdo->prepare("UPDATE users SET reset_token = ?, token_expiry = ? WHERE email = ?");
            $updateStmt->execute([$token, $expiry, $email]);
				$reset_link = "http://localhost:1964/reset_password.php?token=" . $token; 
          
          	/*
          			
          			use whatever mail sending api you want
          			
          			  $mail = new PHPMailer(true); // one of the best i think 
          	
          	*/
          	
          	
          	 $emailBody = '
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .header { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                        .content { background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; }
                        .button { display: inline-block; background: #2563eb; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 20px 0; }
                        .button:hover { background: #1d4ed8; }
                        .footer { background: #f3f4f6; padding: 20px; text-align: center; font-size: 12px; color: #6b7280; border-radius: 0 0 10px 10px; }
                        .warning { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0; }
                        .link { word-break: break-all; background: #f3f4f6; padding: 10px; border-radius: 5px; font-size: 12px; }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <p>Password Reset Request</p>
                        </div>
                        
                        <div class="content">
                            <h2>Hello ' . htmlspecialchars($user['name']) . '!</h2>
                            
                            <p>We received a request to reset your password for your account. Click the button below to reset your password:</p>
                            
                            <p style="text-align: center;">
                                <a href="' . $reset_link . '" class="button">Reset My Password</a>
                            </p>
                            
                            <p><strong>Or copy and paste this link into your browser:</strong></p>
                            <p class="link">' . $reset_link . '</p>
                            
                            <div class="warning">
                                <strong>Important:</strong>
                                <ul style="margin: 10px 0;">
                                    <li>This link will expire in <strong>1 hour</strong></li>
                                    <li>If you didn\'t request this, you can safely ignore this email</li>
                                    <li>For security, don\'t share this link with anyone</li>
                                </ul>
                            </div>
                            
                            <p>Need help? Contact our support team at <a href="mailto:support@admin.com">support@admin.com</a></p>
                        </div>
                        
                        <div class="footer">
                            <p>© '.date('Y').' Commercity. All rights reserved.</p>
                            <p>Address:  New Orleans</p>
                            <p>This is an automated message, please do not reply.</p>
                        </div>
                    </div>
                </body>
                </html>
                ';
         
            $message = "Reset link generated:";
        } else {
          
            $message = "If that email exists, a reset link has been sent.";
        }
    }
}
?>

<div class="container mx-auto px-6 py-16 flex justify-center">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Reset Password</h2>
        
        <?php if($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if($message): ?>
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if($reset_link): ?>
            <div class="bg-gray-100 p-3 rounded mb-4 break-all text-sm">
                <strong>Local Test Link:</strong><br>
                <a href="<?php echo $reset_link; ?>" class="text-blue-600 underline"><?php echo $reset_link; ?></a>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Enter your email</label>
                <input type="email" name="email" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition">Send Reset Link</button>
        </form>
        <p class="text-center text-gray-600 mt-4"><a href="login.php" class="text-blue-600 hover:underline">Back to Login</a></p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
