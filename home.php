<?php
session_start(); // Start the session

// Include database connection
require 'db.php'; // Ensure this file is in the same directory or adjust the path accordingly

// Check if the user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    $_SESSION['error_message'] = "Please log in first!";
    header("Location: index.php");
    exit();
}

// Retrieve user data from the database
if (isset($_SESSION['phone'])) {
    $phone = $_SESSION['phone']; // Assuming phone is stored in session after login
    $stmt = $conn->prepare("SELECT name, email, phone FROM users WHERE phone = :phone");
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['phone'] = $user['phone'];
    } else {
        $_SESSION['error_message'] = "User  not found.";
        header("Location: index.php");
        exit();
    }
} else {
    $_SESSION['error_message'] = "Phone number not set in session.";
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="home.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="nav-bar">
        <div class="tit">
            <p>Blood Bank</p>
        </div>
        <div class="cont">
            <a href="#home">Home</a>
            <a href="recents.html">Recents</a>
            <a href="about.php">About Us</a>
            <a href="form.php">Register</a>
            <a href="index.php">Logout</a>
            <div class="profile-container">
                <i class='bx bxs-user-circle profile-icon'></i>
                <div class="profile-details">
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($_SESSION['phone']); ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="main">
        <div class="content">
            <div class="typing-container">Welcome!</div>
            <div class="matter">
                <p class="slo">"A platform for blood donation and connecting communities in need."</p>
                <p class="def">Join us to become a donor or find the right donor to save a life effortlessly.</p> 
            </div>
            <button class="donate-button" onclick="window.location.href='donors.php'">Search for donor</button>
        </div>
        <img src="home.png" alt="Blood donation illustration" aria-describedby="blood-donation-image">
    </div>

    <div class="toast" id="toast"></div>
    
    <script>
        function showToast(message, type = 'success') {
            const toast = document.getElementById("toast");
            toast.textContent = message;
            toast.className = "toast show " + type;
            setTimeout(() => {
                toast.className = toast.className.replace("show", "");
            }, 3500);
        }

        <?php if (isset($_SESSION['success_message'])): ?>
            showToast(<?php echo json_encode($_SESSION['success_message']); ?>, 'success');
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            showToast(<?php echo json_encode($_SESSION['error_message']); ?>, 'error');
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
    </script>
</body>
</html>