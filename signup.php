<?php
session_start();
include('db.php'); // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim(htmlspecialchars($_POST['name']));
    $phone = trim(htmlspecialchars($_POST['phone']));
    $email = trim(htmlspecialchars($_POST['email']));
    $password = trim($_POST['password']);
    $rePassword = trim($_POST['re-enter-password']);

    // Check if fields are empty
    if (empty($name) || empty($phone) || empty($email) || empty($password) || empty($rePassword)) {
        $_SESSION['error_message'] = "All fields are required.";
    }
    // Validate email format
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Invalid email format.";
    }
    // Validate phone number (10-digit format)
    elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
        $_SESSION['error_message'] = "Invalid phone number.";
    }
    // Check password match
    elseif ($password !== $rePassword) {
        $_SESSION['error_message'] = "Passwords do not match.";
    } else {
        // Check if phone or email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE phone = :phone OR email = :email");
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['error_message'] = "Phone number or email already registered.";
        } else {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user into database
            $stmt = $conn->prepare("INSERT INTO users (name, phone, email, password) VALUES (:name, :phone, :email, :password)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Account created successfully! Please log in.";
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['error_message'] = "Something went wrong. Try again.";
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
    <title>Signup Page</title>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="signup.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="box">
        <div class="signup_box">
            <h2 class="sign_heading">Create Account</h2>
            <form action="signup.php" method="POST">
                <div class="input_box">
                    <input type="text" id="name" name="name" required>
                    <label for="name">Name</label>
                    <i class='bx bxs-user'></i>
                </div>

                <div class="input_box">
                    <input type="tel" id="phone" name="phone" required>
                    <label for="phone">Phone Number</label>
                    <i class='bx bxs-mobile'></i>
                </div>

                <div class="input_box">
                    <input type="email" id="email" name="email" required>
                    <label for="email">E-mail</label>
                    <i class='bx bxl-gmail'></i>
                </div>

                <div class="input_box">
                    <input type="password" id="password" name="password" required>
                    <label for="password">Password</label>
                    <i class='bx bxs-lock-alt'></i>
                </div>

                <div class="input_box">
                    <input type="password" id="re-enter-password" name="re-enter-password" required>
                    <label for="re-enter-password">Re-enter Password</label>
                    <i class='bx bxs-lock-alt'></i>
                </div>

                <button class="btn" type="submit">Create</button>

                <div class="signup">
                    <p>Already have an account? <a href="index.php" class="register-link">Sign in</a></p>
                </div>
            </form>
        </div>

        <div class="info">
            <h2 class="Wel_heading">WELCOME!</h2>
            <p class="des">This blood bank website is a platform that facilitates the connection between donors and recipients.</p>
        </div>
    </div>

    <!-- Toast Message -->
    <div class="toast" id="toast"></div>

    <script>
        function showToast(message, type = 'error') {
            const toast = document.getElementById("toast");
            toast.textContent = message;
            toast.className = "toast show " + type;

            setTimeout(() => {
                toast.classList.remove("show");
            }, 3000);
        }

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
