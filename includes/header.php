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

    <!-- Weather Animations CSS -->
    <style>
        /* Weather animation container */
        .weather-animation-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }

        /* Typewriter cursor */
        .typewriter-cursor {
            display: inline-block;
            width: 2px;
            height: 1em;
            background-color: currentColor;
            margin-left: 2px;
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }

        /* Text gradient effect */
        .text-gradient {
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            display: inline-block;
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
        }
    </style>
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

        /* Dark dropdowns */
        select, select option, .dropdown-menu, .dropdown-item {
            background-color: #1a202c !important;
            color: white !important;
            border-color: rgba(255, 255, 255, 0.2) !important;
        }

        select:focus {
            border-color: var(--color-accent) !important;
            ring-color: var(--color-accent) !important;
        }

        /* Fix for language selector and chatbot dropdowns */
        #language-selector, #chatbot-language {
            background-color: rgba(26, 32, 44, 0.9) !important;
            color: white !important;
        }

        /* Force dark background for all select elements */
        select {
            background-color: #1a202c !important;
            color: white !important;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
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
                        <a href="<?php echo $base_url; ?>" class="block py-2 pl-3 pr-4 hover:text-accent rounded md:p-0" aria-current="page"><span data-translate="nav-home">Home</span></a>
                    </li>
                    <li>
                        <a href="<?php echo $base_url; ?>/pages/weather.php" class="block py-2 pl-3 pr-4 hover:text-accent rounded md:p-0"><span data-translate="nav-weather">Weather</span></a>
                    </li>
                    <li>
                        <a href="<?php echo $base_url; ?>/pages/about.php" class="block py-2 pl-3 pr-4 hover:text-accent rounded md:p-0"><span data-translate="nav-about">About</span></a>
                    </li>
                    <li>
                        <a href="<?php echo $base_url; ?>/pages/contact.php" class="block py-2 pl-3 pr-4 hover:text-accent rounded md:p-0"><span data-translate="nav-contact">Contact</span></a>
                    </li>
                    <?php if ($isLoggedIn): ?>
                    <li>
                        <a href="<?php echo $base_url; ?>/dashboard/index.php" class="block py-2 pl-3 pr-4 hover:text-accent rounded md:p-0"><span data-translate="nav-dashboard">Dashboard</span></a>
                    </li>
                    <li>
                        <a href="<?php echo $base_url; ?>/auth/logout.php" class="block py-2 pl-3 pr-4 text-red-400 hover:text-red-300 rounded md:p-0"><span data-translate="nav-logout">Logout</span></a>
                    </li>
                    <?php else: ?>
                    <li>
                        <a href="<?php echo $base_url; ?>/auth/login.php" class="block py-2 pl-3 pr-4 hover:text-accent rounded md:p-0"><span data-translate="nav-login">Login</span></a>
                    </li>
                    <li>
                        <a href="<?php echo $base_url; ?>/auth/register.php" class="block py-2 pl-3 pr-4 bg-accent hover:bg-purple-500 rounded-full px-4 py-1 transition-colors"><span data-translate="nav-register">Register</span></a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <div class="container mx-auto pt-24 px-4 pb-8">
