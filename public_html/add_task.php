<?php
session_start();
require 'config/db.php';
require 'functions/helpers.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['task']);

    if (!empty($title)) {
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, status) VALUES (?, ?, 'pending')");
        $stmt->execute([$_SESSION['user_id'], $title]);
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bluey: '#3D41C1',
                        navy: '#171950',
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
                        fadeIn: 'fadeIn 0.5s ease-out',
                        slideIn: 'slideIn 0.5s ease-out',
                    }
                }
            }
        }
    </script>
    <script src="https://kit.fontawesome.com/f62928dd38.js" crossorigin="anonymous"></script>

</head>

<body class="bg-[#EBECFF] overflow-hidden">

    <?php require 'navbar.php'; ?>

    <div class="flex flex-col justify-center items-center mx-auto mt-4 p-4 font-montserrat max-w-full sm:max-w-lg md:max-w-xl lg:max-w-2xl">
        <div class="mb-2 animate-slideIn">
            <img src="robot.png" class="w-[200px] sm:w-[250px] md:w-[300px]" alt="Robot Image">
        </div>
        <h1 class="text-2xl sm:text-3xl font-bold mb-4 text-center text-bluey animate-slideIn">
            What are you <br /> going to do today?
        </h1>

        <form method="POST" class="w-full">
            <div class="flex flex-col animate-slideIn w-full justify-center">
                <div class="flex justify-center mt-4">
                    <input type="text" name="task" class="h-[40px] sm:h-[45px] w-full md:w-[400px] p-4 text-left form-input mt-1 rounded-[30px] border border-gray-300 text-sm md:text-base" placeholder="Insert a new task" required>
                </div>
                <div class="flex justify-center mt-4">
                    <button type="submit" class="w-[120px] sm:w-[150px] rounded-[30px] text-center bg-bluey text-white font-bold py-2 px-4 hover:bg-blue-400">Add task</button>
                </div>
            </div>
        </form>
    </div>


    <div id="taskPopup" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden font-montserrat">
        <div class="bg-white rounded-[20px] shadow-lg p-6 sm:p-8 text-center max-w-[90%] sm:max-w-md mx-auto">
            <h2 class="text-xl sm:text-2xl font-bold text-bluey mb-4">Task has been Successfully Added!</h2>
            <img src="added1.png" alt="Task Added" class="mx-auto mb-4 w-[150px] sm:w-[200px]" />
            <div class="flex flex-col space-y-2 sm:space-y-0 justify-center sm:space-x-4">
                <div class="flex justify-center mb-2">
                    <button id="confirmButton" class="w-[150px] sm:w-[150px] bg-bluey text-white py-2 px-4 rounded-[30px] hover:bg-blue-500 font-bold mb-2 sm:mb-0">
                        Confirm
                    </button>
                </div>
                <div class="flex justify-center">
                    <button id="dashboardButton" class="sm:w-[250px] bg-navy text-white py-2 px-4 rounded-[30px] hover:bg-blue-700 font-bold">
                        Go back to Dashboard
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelector("form").addEventListener("submit", function(e) {
            e.preventDefault();

            document.getElementById("taskPopup").classList.remove("hidden");

            document.getElementById("confirmButton").addEventListener("click", function() {
                e.target.submit();
            });

            document.getElementById("dashboardButton").addEventListener("click", function() {
                window.location.href = 'index.php';
            });
        });
    </script>

</body>

</html>