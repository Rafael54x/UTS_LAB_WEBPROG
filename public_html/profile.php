<?php
session_start();
require 'config/db.php';
require 'functions/helpers.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$default_picture = 'uploads/profile_pictures/default.png';
if (empty($user['profile_picture']) || !file_exists($user['profile_picture'])) {
    $user['profile_picture'] = $default_picture;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: #EBECFF;
            font-family: 'Montserrat', sans-serif;
        }

        .profile-card {
            background: white;
            border-radius: 18px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin: 1rem auto;
        }

        .profile-picture {
            width: 200px;
            height: 200px;
            border-radius: 14px;
            object-fit: cover;
        }

        .edit-button {
            color: #4E46E5;
            border: 2px solid #4E46E5;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .edit-button:hover {
            background: #4E46E5;
            color: white;
        }
    </style>
</head>

<body>
    <?php require 'navbar.php'; ?>

    <div class="px-4 mt-10 flex justify-center ">
        <div class="profile-card w-full max-w-[700px] h-[400px] max-sm:h-auto flex flex-wrap items-center justify-evenly">
            <div class="flex md:space-x-28 flex-wrap max-sm:justify-center">
                <div class="flex flex-col lg:ms-[-20px] ">
                    <div class="mb-8">
                        <h1 class="text-xl max-sm:text-[24px] font-bold text-[#4E46E5] max-sm:text-center">My Profile</h1>
                    </div>
                    <img src="<?= htmlspecialchars($user['profile_picture']) ?>"
                        alt="Profile Picture"
                        class="profile-picture">
                </div>

                <div class=" mt-16 max-sm:justify-center">
                    <div class="mb-2">
                        <h2 class="text-lg max-sm:text-center font-semibold text-[#4E46E5]">Username</h2>
                        <p class="text-gray-700 max-sm:text-center"><?= htmlspecialchars($user['username']) ?></p>
                    </div>

                    <div class="my-2 max-sm:justify-center">
                        <h2 class="text-lg max-sm:text-center font-semibold text-[#4E46E5]">Email</h2>
                        <p class="text-gray-700 max-sm:text-center"><?= htmlspecialchars($user['email']) ?></p>
                    </div>

                    <div class="my-2 max-sm:justify-center">
                        <h2 class="text-lg max-sm:text-center font-semibold text-[#4E46E5]">Password</h2>
                        <p class="text-gray-700 max-sm:text-center">********</p>
                    </div>


                    <div class="pt-4 mt-8">
                        <a href="edit_profile.php" class="edit-button">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>