<?php
session_start();
require 'config/db.php';
require 'functions/helpers.php';

$username = '';
$email = '';
$password = '';
$confirm_password = '';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);
    $confirm_password = sanitizeInput($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $error = "Password tidak sama.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->rowCount() > 0) {
            $error = "Username atau email sudah terdaftar.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            
            if ($stmt->execute([$username, $email, $hashed_password])) {
                header("Location: login.php");
                exit();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideIn: {
                            '0%': { transform: 'translateY(100px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
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
    <script src="https://kit.fontawesome.com/f62928dd38.js" crossorigin="anonymous"></script>
</head>

<body class="h-screen flex overflow-hidden font-montserrat">

    
    <div class="relative w-full h-full flex justify-center items-center bg-bluey md:h-1/2 lg:w-[900px] lg:w-1/2 lg:h-full animate-fadeIn">
        <div class="absolute p-4 top-4 left-4 lg:top-auto lg:left-auto md:flex md:justify-center animate-slideIn">
            <img src="logo.svg" alt="logo" class="h-[80px] md:h-[120px]">
        </div>
        <img class="w-full h-full object-cover md:hidden lg:block" src="bg_logo.png" alt="bg">
    </div>

  
    <div class="absolute inset-0 flex items-center justify-center md:static lg:relative lg:w-[500px] lg:flex lg:items-center max-md:justify-center animate-slideIn">
        <div class="max-w-md w-full md:w-3/4 p-6 bg-white max-md:rounded-lg max-md:shadow-lg max-sm:m-4 md:absolute md:bg-opacity-80 md:backdrop-filter md:backdrop-blur-sm animate-fadeIn">
            <h2 class="text-3xl md:text-4xl font-montserrat font-bold text-center mb-6 md:mb-10 text-bluey tracking-tight animate-slideIn">Create Account</h2>

            <?php if (!empty($error)): ?>
                <div class="bg-red-500 text-white p-3 rounded mb-4 animate-slideIn"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="bg-green-500 text-white p-3 rounded mb-4 animate-slideIn"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="relative mb-4 animate-slideIn">
                    <i class="fa fa-user-o absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" class="p-2 px-4 pl-10 mt-1 block w-full h-12 border border-gray-300 bg-gray-200 rounded-lg" id="username" name="username" placeholder="Username" required>
                </div>

                <div class="relative mb-4 animate-slideIn">
                    <i class="fa fa-envelope-o absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="email" class="p-2 px-4 pl-10 mt-1 block w-full h-12 border border-gray-300 bg-gray-200 rounded-lg" id="email" name="email" placeholder="Email" required>
                </div>

                <div class="relative mb-4 animate-slideIn">
                    <i class="fa fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="password" class="p-2 px-4 pl-10 h-12 mt-1 block w-full border border-gray-300 bg-gray-200 rounded-lg" id="password" name="password" placeholder="Password" required>
                </div>

                <div class="relative mb-4 animate-slideIn">
                    <i class="fa fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="password" class="p-2 px-4 pl-10 h-12 mt-1 block w-full border border-gray-300 bg-gray-200 rounded-lg" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                </div>

                <div class="flex justify-center animate-slideIn">
                    <button type="submit" class="w-2/6 bg-bluey text-white font-bold py-2 rounded-[40px] hover:bg-blue-800 transition duration-200">Register</button>
                </div>

                <p class="mt-4 text-center text-sm font-bold animate-slideIn">Already have an account? <a href="login.php" class="text-blue-600 hover:underline">Login here</a></p>
            </form>
        </div>
    </div>

</body>

</html>
