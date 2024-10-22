<?php
session_start();
require 'config/db.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars($_POST['email']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $token = bin2hex(random_bytes(50));

        $stmt = $pdo->prepare("UPDATE users SET reset_token = :token, reset_expiry = :expiry WHERE email = :email");
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $stmt->execute(['token' => $token, 'expiry' => $expiry, 'email' => $email]);

        $resetLink = "http://localhost.com/reset_password.php?token=" . $token;

        $subject = "Password Reset Request";
        $message = "Hi, \n\nYou requested a password reset. Click the link below to reset your password:\n\n" . $resetLink . "\n\nIf you did not request this, please ignore this email.";

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = '4bfce330b2b86f';
            $mail->Password = '235d5edb64dfe6';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('no-reply@yourdomain.com', 'Mailer');
            $mail->addAddress($email);

            $mail->isHTML(false);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
            $success = "Password reset link has been sent to your email.";
        } catch (Exception $e) {
            $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $error = "No account found with that email.";
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
    <title>Forgot Password</title>
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
                    }
                }
            }
        }
    </script>
    <script src="https://kit.fontawesome.com/f62928dd38.js" crossorigin="anonymous"></script>
</head>

<body class="h-screen flex overflow-hidden font-montserrat">

    <div class="relative w-full h-full flex justify-center items-center bg-bluey md:h-1/2 lg:w-[900px] lg:w-1/2 lg:h-full">
        <div class="absolute p-4 top-4 left-4 lg:top-auto lg:left-auto md:flex md:justify-center">
            <img src="logo.svg" alt="logo" class="h-[80px] md:h-[120px]">
        </div>
        <img class="w-full h-full object-cover md:hidden lg:block" src="bg_logo.png" alt="bg">
    </div>

    <div class="absolute inset-0 flex items-center justify-center md:static lg:relative lg:w-[500px] lg:flex lg:items-center max-md:justify-center">
        <div class="max-w-md w-full md:w-3/4 p-6 bg-white max-md:rounded-lg max-md:shadow-lg max-sm:m-4 md:absolute md:bg-opacity-80 md:backdrop-filter md:backdrop-blur-sm">
            <h2 class="text-3xl md:text-4xl font-montserrat font-bold text-center mb-6 md:mb-10 text-bluey tracking-tight">Forgot Password</h2>

            <?php if (isset($success)): ?>
                <p class="text-green-500 text-center mb-4"><?= $success ?></p>
                <p class="text-center mb-4">You can now <a href="login.php" class="text-blue-600 hover:underline">go back to login</a>.</p>
            <?php elseif (isset($error)): ?>
                <p class="text-red-500 text-center mb-4"><?= $error ?></p>
                <p class="text-center mb-4">Please try again or <a href="login.php" class="text-blue-600 hover:underline">return to login</a>.</p>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="relative mb-4">
                    <input type="email" name="email" id="email" required class="p-2 px-4 mt-1 block w-full h-12 border border-gray-300 bg-gray-200 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Email">
                </div>

                <div class="flex justify-center">
                    <button type="submit" class="w-2/6 bg-bluey text-white font-bold py-2 rounded-[40px] hover:bg-blue-800 transition duration-200">Continue</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>
