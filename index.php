<?php
session_start(); // Start session

// Include database connection
require 'db.php'; // Ensure this file exists and has a valid PDO connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['phone']) && isset($_POST['password'])) {
        // Retrieve user input
        $phone = trim($_POST['phone']);
        $password = trim($_POST['password']);

        if (!empty($phone) && !empty($password)) {
            // Prepare SQL to prevent SQL Injection
            $stmt = $conn->prepare("SELECT * FROM users WHERE phone = :phone LIMIT 1");
            $stmt->bindValue(':phone', $phone);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    // Store user session
                    $_SESSION['user_logged_in'] = true;
                    $_SESSION['phone'] = $phone;
                    $_SESSION['success_message'] = "Login successful!";
                    header("Location: home.php"); // Redirect to home
                    exit();
                } else {
                    $_SESSION['error_message'] = "Invalid password.";
                }
            } else {
                $_SESSION['error_message'] = "Invalid phone number.";
            }
        } else {
            $_SESSION['error_message'] = "Please fill in both fields.";
        }
    } else {
        $_SESSION['error_message'] = "Form data missing.";
    }
    header("Location: index.php"); // Redirect back to login page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="login.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="box">
        <div class="login_box">
            <h2 class="log_heading">Login</h2>
            <form action="index.php" method="POST">
                <div class="input_box">
                    <input type="tel" id="phone" name="phone" required>
                    <label for="phone">Phone number</label>
                    <i class='bx bxs-mobile'></i>
                </div>

                <div class="input_box">
                    <input type="password" id="password" name="password" required>
                    <label for="password">Password</label>
                    <i class='bx bxs-lock-alt'></i>
                </div>

                <button class="btn" type="submit">Login</button>

                <div class="signup">
                    <p>Don't have an account? <a href="signup.php" class="register-link">Sign Up</a></p>
                </div>
            </form>
        </div>

        <div class="info">
            <h2 class="Wel_heading">WELCOME!</h2>
            <p class="des">This blood bank website is a platform that facilitates the connection between donors and recipients.</p>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <script>
        function showToast(message, type = 'error') {
            const toast = document.getElementById("toast");
            toast.textContent = message;
            toast.className = "toast show " + type;
            setTimeout(() => {
                toast.className = toast.className.replace("show", "");
            }, 3000); // Show for 3 seconds
        }

        // Check for session messages and show toast
        <?php if (isset($_SESSION['error_message'])): ?>
            showToast(<?php echo json_encode($_SESSION['error_message']); ?>, 'error');
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success_message'])): ?>
            showToast(<?php echo json_encode($_SESSION['success_message']); ?>, 'success');
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
    </script>
</body>
</html>