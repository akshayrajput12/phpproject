<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Base URL for the application
$base_url = "http://localhost/farmer";

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crop Guidance and Farmer's Friend</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#6366f1',
                        secondary: '#8b5cf6',
                        accent: '#d946ef',
                        dark: '#1e293b',
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/animations.css">
    <style>
        /* Glass morphism effect */
        .glass {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hover-scale {
            transition: transform 0.3s ease;
        }

        .hover-scale:hover {
            transform: scale(1.05);
        }

        /* Sliding alert */
        .slide-in {
            animation: slideIn 0.5s forwards;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Background gradient */
        body {
            background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
            min-height: 100vh;
            color: #e2e8f0;
        }
    </style>
</head>
<body class="font-sans">
    <!-- Navigation -->
    <nav class="glass fixed w-full z-10 px-4 py-2.5">
        <div class="container mx-auto flex flex-wrap justify-between items-center">
            <a href="<?php echo $base_url; ?>" class="flex items-center space-x-3">
                <i class="fas fa-seedling text-accent text-2xl"></i>
                <span class="self-center text-xl font-semibold whitespace-nowrap">Farmer's Friend</span>
            </a>

            <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm rounded-lg md:hidden focus:outline-none focus:ring-2 focus:ring-gray-200" aria-controls="navbar-default" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <i class="fas fa-bars"></i>
            </button>

            <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                <ul class="flex flex-col p-4 md:p-0 mt-4 md:flex-row md:space-x-8 md:mt-0 md:border-0">
                    <li>
                        <a href="<?php echo $base_url; ?>" class="block py-2 pl-3 pr-4 hover:text-accent rounded md:p-0" aria-current="page">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo $base_url; ?>/pages/weather.php" class="block py-2 pl-3 pr-4 hover:text-accent rounded md:p-0">Weather</a>
                    </li>
                    <li>
                        <a href="<?php echo $base_url; ?>/pages/about.php" class="block py-2 pl-3 pr-4 hover:text-accent rounded md:p-0">About</a>
                    </li>
                    <li>
                        <a href="<?php echo $base_url; ?>/pages/contact.php" class="block py-2 pl-3 pr-4 hover:text-accent rounded md:p-0">Contact</a>
                    </li>
                    <?php if ($isLoggedIn): ?>
                    <li>
                        <a href="<?php echo $base_url; ?>/dashboard/index.php" class="block py-2 pl-3 pr-4 hover:text-accent rounded md:p-0">Dashboard</a>
                    </li>
                    <li>
                        <a href="<?php echo $base_url; ?>/auth/logout.php" class="block py-2 pl-3 pr-4 text-red-400 hover:text-red-300 rounded md:p-0">Logout</a>
                    </li>
                    <?php else: ?>
                    <li>
                        <a href="<?php echo $base_url; ?>/auth/login.php" class="block py-2 pl-3 pr-4 hover:text-accent rounded md:p-0">Login</a>
                    </li>
                    <li>
                        <a href="<?php echo $base_url; ?>/auth/register.php" class="block py-2 pl-3 pr-4 bg-accent hover:bg-purple-500 rounded-full px-4 py-1 transition-colors">Register</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <div class="container mx-auto pt-24 px-4 pb-8">
