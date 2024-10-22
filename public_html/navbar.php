<?php
require 'config/db.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$currentPage = basename($_SERVER['PHP_SELF'], ".php");

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$default_picture = 'uploads/profile_pictures/default.jpg';
if (empty($user['profile_picture']) || !file_exists($user['profile_picture'])) {
    $user['profile_picture'] = $default_picture;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Navbar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/f62928dd38.js" crossorigin="anonymous"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bluey: '#3D41C1',
                    },
                    fontFamily: {
                        'montserrat': 'Montserrat'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            }
                        },
                        slideIn: {
                            '0%': {
                                transform: 'translateY(100px)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            }
                        }
                    },
                    animation: {
                        fadeIn: 'fadeIn 1s ease-out',
                        slideIn: 'slideIn 1s ease-out',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 font-montserrat">

    <nav class="bg-gradient-to-r from-[#252774] to-[#6467CD] shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-[90px]">
                <div class="flex items-center space-x-8">
                    <div class="flex-shrink-0 mb-2">
                        <a href="index.php">
                            <img class="w-[80px]" src="logo.svg" alt="Logo">
                        </a>
                    </div>
                    
                    <div class="hidden md:flex space-x-8 font-bold font-montserrat">
                        <a href="index.php" class="flex items-center space-x-2 text-white text-opacity-70 hover:text-gray-300 transition">
                            <i class="fa-solid fa-grip"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="add_task.php" class="flex items-center space-x-2 text-white text-opacity-70 hover:text-gray-300 transition">
                            <i class="fas fa-plus-circle"></i>
                            <span>Add Task</span>
                        </a>
                    </div>
                </div>

              
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-toggle" class="text-white focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>

                <div class="relative max-md:hidden max-sm:hidden">
                    <div class="flex items-center space-x-2 cursor-pointer">
                        <div id="user-menu-toggle">
                            <span class="text-white me-2">Hello, <b><?= htmlspecialchars($user['username']) ?></b></span>
                            <i class="fas fa-chevron-down text-white text-sm"></i>
                        </div>
                        <div>
                            <a href="profile.php">
                                <img src="<?= htmlspecialchars($user['profile_picture']) ?>" class="h-12 w-12 ms-2 rounded-full border border-2 border-white ml-4" alt="User Avatar">
                            </a>
                        </div>
                    </div>

                    <div id="user-menu" class="hidden absolute right-0 mt-2 w-[200px] bg-white rounded-lg shadow-lg py-2 z-20 font-montserrat font-bold">
                        <a href="profile.php" class="block px-4 py-2 text-blue-600 hover:bg-gray-200">My Profile</a>
                        <hr>
                        <a href="logout.php" class="block px-4 py-2 text-blue-600 hover:bg-gray-200">Log Out</a>
                    </div>
                </div>
            </div>
        </div>

       
        <div id="mobile-menu" class="hidden md:hidden bg-gradient-to-r from-[#252774] to-[#6467CD] px-4 pt-2 pb-3 space-y-2 text-center font-montserrat">
            <hr>
            <a href="index.php" class="block text-white text-opacity-70 hover:text-gray-300 transition font-bold">Dashboard</a>
            <a href="add_task.php" class="block text-white text-opacity-70 hover:text-gray-300 transition font-bold">Add Task</a>
            <a href="profile.php" class="block text-white text-opacity-70 hover:text-gray-300 transition font-bold">My Profile</a>
            <a href="logout.php" class="block text-white text-opacity-70 hover:text-gray-300 transition font-bold mt-2">Log Out</a>
        </div>
    </nav>

    <script>
    
        document.getElementById('user-menu-toggle').addEventListener('click', function() {
            const menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
        });

  
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('user-menu');
            const toggle = document.getElementById('user-menu-toggle');
            if (!toggle.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });


        document.getElementById('mobile-menu-toggle').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });
    </script>

</body>

</html>
