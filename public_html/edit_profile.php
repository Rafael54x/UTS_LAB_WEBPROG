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

$success = '';
$error = '';

$default_picture = 'uploads/profile_pictures/default.jpg';
if (empty($user['profile_picture']) || !file_exists($user['profile_picture'])) {
    $user['profile_picture'] = $default_picture;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $current_password = sanitizeInput($_POST['current_password']);
    $new_password = sanitizeInput($_POST['new_password']);
    $confirm_password = sanitizeInput($_POST['confirm_password']);

    $profile_picture = $user['profile_picture'];
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/profile_pictures/';
        $file_name = uniqid() . '_' . basename($_FILES['profile_picture']['name']);
        $target_file = $upload_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($file_type, $allowed_types)) {
            $error = "Unsupported file format. Only JPG, JPEG, PNG, and GIF are allowed.";
        } else {
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                $profile_picture = $target_file;
            } else {
                $error = "Failed to upload the image. Error code: " . $_FILES['profile_picture']['error'];
            }
            
        }
    }
    

    if (empty($error)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        } elseif (!password_verify($current_password, $user['password'])) {
            $error = "Current password is incorrect.";
        } elseif ($new_password !== $confirm_password) {
            $error = "New passwords do not match.";
        } else {
            $hashed_password = !empty($new_password) ?
                password_hash($new_password, PASSWORD_DEFAULT) :
                $user['password'];

            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, password = ?, profile_picture = ? WHERE id = ?");
            $stmt->execute([$username, $email, $hashed_password, $profile_picture, $_SESSION['user_id']]);
            $success = "Profile updated successfully.";

            
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

        .input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .input:focus {
            outline: none;
            border-color: #4E46E5;
        }
    </style>
</head>

<body>
    <?php require 'navbar.php'; ?>

    <div class="px-4 mt-10 flex justify-center ">
        <div class="profile-card w-full max-w-[700px] h-[auto] max-sm:h-auto flex flex-wrap items-center justify-evenly">
            <div class="flex md:space-x-28 flex-wrap max-sm:justify-center">
                <div class="flex flex-col lg:ms-[-20px]">
                    <div class="mb-8">
                        <h1 class="text-xl max-sm:text-[24px] font-bold text-[#4E46E5] max-sm:text-center">Edit Profile</h1>
                    </div>
                    <div class="profile-picture-container relative mb-8">
                        <img src="<?= htmlspecialchars($user['profile_picture']) ?>"
                            alt="Profile Picture"
                            class="profile-picture">


                        <label for="profile_picture" class="camera-icon absolute top-0 left-0 p-4 cursor-pointer">
                            <i class="fa-solid fa-camera text-xl"></i>
                        </label>

                        <input type="file" id="profile_picture" name="profile_picture" class="hidden" accept="image/*">
                    </div>

                </div>

                <div class="flex flex-col max-sm:justify-center lg:mt-16">
                    <form method="POST" enctype="multipart/form-data" class="space-y-6 ">
                        <div>
                            <label class="block font-montserrat text-[#4E46E5] font-bold text-lg mb-2">
                                Username:
                            </label>
                            <input type="text"
                                name="username"
                                value="<?= htmlspecialchars($user['username']) ?>"
                                class="w-full px-4 py-3 bg-[#F5F5F5] rounded-lg border-2 border-gray-300 font-montserrat">
                        </div>

                        <div>
                            <label class="block font-montserrat font-bold text-[#4E46E5] text-lg mb-2">
                                Email:
                            </label>
                            <input type="email"
                                name="email"
                                value="<?= htmlspecialchars($user['email']) ?>"
                                class="w-full px-4 py-3 bg-[#F5F5F5] rounded-lg border-2 border-gray-300 font-montserrat">
                        </div>

                        <div>
                            <label class="block font-montserrat font-bold text-[#4E46E5] text-lg mb-2">
                                Current Password:
                            </label>
                            <input type="password"
                                name="current_password"
                                class="w-full px-4 py-3 bg-[#F5F5F5] rounded-lg border-2 border-gray-300 font-montserrat">
                        </div>

                        <div>
                            <label class="block font-montserrat font-bold text-[#4E46E5] text-lg mb-2">
                                New Password:
                            </label>
                            <input type="password"
                                name="new_password"
                                class="w-full px-4 py-3 bg-[#F5F5F5] rounded-lg border-2 border-gray-300 font-montserrat">
                        </div>

                        <div>
                            <label class="block font-montserrat font-bold text-[#4E46E5] text-lg mb-2">
                                Confirm New Password:
                            </label>
                            <input type="password"
                                name="confirm_password"
                                class="w-full px-4 py-3 bg-[#F5F5F5] rounded-lg border-2 border-gray-300 font-montserrat">
                        </div>

                        <div class="flex gap-4">
                            <button type="submit"
                                name="update"
                                class="flex-1 bg-[#4E46E5] h-[45px] hover:bg-primary text-white p-2 rounded-lg transition-colors font-montserrat font-bold">
                                Save
                            </button>
                            <a href="profile.php"
                                class="flex-1 bg-white h-[45px] text-[#4E46E5] border-2 border-[#4E46E5] p-2 rounded-lg transition-colors font-montserrat font-bold text-center">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>