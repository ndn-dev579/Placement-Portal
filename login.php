<?php
session_start();
require_once 'db-functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = login($email, $password);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header('Location: admin/admin_dashboard.php');
            exit;
        } elseif ($user['role'] === 'student') {
            switch ($user['login_status']) {
                case 'approved':
                    header('Location: student/dashboard.php');
                    exit;
                case 'pending':
                    header('Location: pending-approval.php');
                    exit;
                // In login.php
                case 'rejected':
                    $_SESSION['resubmit_user_id'] = $user['id']; // Use this instead of a generic login
                    header('Location: student-resubmit.php');
                    exit;
            }
        }
    } else {
        $error = '❌ Invalid email or password.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login_form.css">

    
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-illustration {
            max-width: 600px;
            margin-right: 50px; /* Adjusts space between image and form */
        }

        

        
    </style>
</head>

<body>




<img src="pictures/login-illustration.png" alt="Illustration" class="login-illustration">

    <div class="login-container">
        <h2>Sign In</h2>

        <?php if (!empty($error)): ?>
            <p style="color: red; font-weight: bold; text-align: center;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="username@example.com" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Password" required>

            <button type="submit" class="login-btn">Login</button>
        </form>

        <p style="text-align: center; margin-top: 20px;">
            Don't have an account? <a href="student-registration.php">Sign up here</a>
        </p>
    </div>
</body>

</html>