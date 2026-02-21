<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent Your Ride</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800">

    
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
           
            <a href="index.php" class="text-2xl font-bold text-blue-600 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                CarLink
            </a>

           
            <div class="hidden md:flex space-x-8 items-center">
                <a href="index.php" class="text-gray-600 hover:text-blue-600 transition">Home</a>
                <a href="#" class="text-gray-600 hover:text-blue-600 transition">Cars</a>
                <a href="#" class="text-gray-600 hover:text-blue-600 transition">Services</a>
            </div>

          
            <div>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="dashboard.php" class="text-blue-600 font-semibold hover:underline">Dashboard</a>
                    <a href="logout.php" class="ml-4 bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="text-gray-600 hover:text-blue-600 font-semibold mr-4">Sign In</a>
                    <a href="register.php" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">Get Started</a>
                <?php endif; ?>
            </div>
            
            
        </div>
    </nav>
