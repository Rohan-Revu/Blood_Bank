<?php
// Include the database connection file
include 'db.php';

// Initialize variables for messages
$successMessage = "";
$errorMessage = "";
$redirect = false;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $age = (int)$_POST['age'];
    $weight = (int)$_POST['weight'];
    $gender = $_POST['gender'];
    $blood_group = $_POST['blood_group'];
    $disease = $_POST['disease'];
    $state = $_POST['state'];
    $city = $_POST['city'];

    // Check if the disease field is not "None"
    if ($disease !== "None" || $age<17 || $weight<50) {
        $errorMessage = "Requirements not met to register as a donor.";
        $redirect = true; // Set redirect flag
    } else {
        try {
            // Check if phone number already exists
            $sql = "SELECT * FROM donors WHERE phone = :phone";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':phone', $phone);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $errorMessage = "Already registered as a donor.";
                $redirect = true; // Set redirect flag
            } else {
                // Insert new donor using a prepared statement
                $sql = "INSERT INTO donors (name, phone, email, age, weight, gender, blood_group, disease, state, city) 
                        VALUES (:name, :phone, :email, :age, :weight, :gender, :blood_group, :disease, :state, :city)";
                
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':age', $age);
                $stmt->bindParam(':weight', $weight);
                $stmt->bindParam(':gender', $gender);
                $stmt->bindParam(':blood_group', $blood_group);
                $stmt->bindParam(':disease', $disease);
                $stmt->bindParam(':state', $state);
                $stmt->bindParam(':city', $city);
                
                if ($stmt->execute()) {
                    $successMessage = "Registration successful!";
                    $redirect = true;
                } else {
                    $errorMessage = "Error in registration.";
                    $redirect = true;
                }
            }
        } catch (PDOException $e) {
            $errorMessage = "Database Error: " . $e->getMessage();
        }
    }
}

// Close the database connection
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as Donor</title>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="form.css?v=<?php echo time(); ?>">
    <script src="form.js"></script>
</head>
<body>

    <div class="box">
        <div class="top">
            <div class="title"><h2>Register</h2><h5>As a donor</h5></div>
            <div class="ima"><img src="form.png" alt="Donor Registration"></div>
        </div>

        <p>"By completing this form, you will join our community of donors and make a valuable contribution to saving lives. <i class='bx bxs-donate-heart'></i>" </p>

        <div class="signup_box">
            <form action="" method="POST">
            <div class="input_box">
                    <input type="text" id="name" name="name" required>
                    <label for="name">Name</label>
                    <i class='bx bxs-user'></i>
            </div>

            <div class="input_box">
                    <input type="tel" id="phone" name="phone" required>
                    <label for="phone">Phone number</label>
                    <i class='bx bxs-mobile'></i>
            </div>

            <div class="input_box">
                    <input type="email" id="email" name="email" required>
                    <label for="email">E-mail</label>
                    <i class='bx bxl-gmail'></i>
            </div>

            <div class="input_box">
                    <input type="number" id="age" min="1" name="age" required>
                    <label for="age">Age</label>
                    <i class='bx bx-male'></i>
            </div>

            <div class="input_box">
                    <input type="number" id="weight" min="1" name="weight" required>
                    <label for="weight">Weight (in Kgs)</label>
                    <i class='bx bxs-album'></i>
            </div>

            <div class="gender">
                    <p>Gender<i class='bx bx-male-female'></i></p>
                    <input type="radio" id="male" name="gender" value="male" required>
                    <label for="male">Male</label>

                    <input type="radio" id="female" name="gender" value="female" required>
                    <label for="female">Female</label>
            </div>

            <div class="blood_group">
                    <label for="blood_group">Choose your blood group<i class='bx bxs-heart-circle'></i></label><br>
                    <select id="blood_group" name="blood_group" required>
                        <option value="" disabled selected>Blood group</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
            </div>

            <div class="disease">
                    <label for="disease">Select condition<i class='bx bxs-sleepy'></i></label><br>
                    <select id="disease" name="disease" required>
                        <option value="" disabled selected>Disease</option>
                        <option value="None">None</option>
                        <option value="Cancer">Cancer</option>
                        <option value="Diabetes">Diabetes</option>
                        <option value="Blood disorder">Blood Disorder</option>
                        <option value="HIV">HIV</option>
                    </select>
            </div>

            <div class="state">
                    <label for="state">Choose your state<i class='bx bx-current-location' ></i></label><br>
                    <select id="state" name="state" required>
                        <option value="" disabled selected>State</option>
                        <option value="AP">Andhra Pradesh</option>
                        <option value="AR">Arunachal Pradesh</option>
                        <option value="AS">Assam</option>
                        <option value="BR">Bihar</option>
                        <option value="CT">Chhattisgarh</option>
                        <option value="GA">Gujarat</option>
                        <option value="HR">Haryana</option>
                        <option value="HP">Himachal Pradesh</option>
                        <option value="JK">Jammu and Kashmir</option>
                        <option value="GA">Goa</option>
                        <option value="JH">Jharkhand</option>
                        <option value="KA">Karnataka</option>
                        <option value="KL">Kerala</option>
                        <option value="MP">Madhya Pradesh</option>
                        <option value="MH">Maharashtra</option>
                        <option value="MN">Manipur</option>
                        <option value="ML">Meghalaya</option>
                        <option value="MZ">Mizoram</option>
                        <option value="NL">Nagaland</option>
                        <option value="OR">Odisha</option>
                        <option value="PB">Punjab</option>
                        <option value="RJ">Rajasthan</option>
                        <option value="SK">Sikkim</option>
                        <option value="TN">Tamil Nadu</option>
                        <option value="TG">Telangana</option>
                        <option value="TR">Tripura</option>
                        <option value="UT">Uttarakhand</option>
                        <option value="UP">Uttar Pradesh</option>
                        <option value="WB">West Bengal</option>
                        <option value="AN">Andaman and Nicobar</option>
                        <option value="CH">Chandigarh</option>
                        <option value="DN">Dadra Nagar Haveli</option>
                        <option value="DD">Daman and Diu</option>
                        <option value="DL">Delhi</option>
                        <option value="LD">Lakshadweep</option>
                        <option value="PY">Puducherry</option>
                    </select>
            </div>
            <div class="input_box">
                    <input type="text" id="city" name="city" required>
                    <label for="city">City/village</label>
                    <i class='bx bxs-edit-location'></i>
            </div>
                <button class="btn" type="submit">Register</button>
            </form>
        </div>
    </div>

    <!-- Toast message for success or error -->
    <div id="toast" class="toast <?php echo $successMessage ? 'success' : ($errorMessage ? 'error' : ''); ?>">
        <?php echo $successMessage ? $successMessage : $errorMessage; ?>
    </div>

    <script>
        // Show toast message
        window.onload = function() {
            var toast = document.getElementById("toast");
            if (toast.innerHTML) {
                toast.className = toast.className + ' show';
                setTimeout(function() {
                    toast.className = toast.className.replace(" show", "");

                    <?php if ($redirect): ?>
                        window.location.href = "home.php"; // Redirect to home page
                    <?php endif; ?>
                }, 1500); // Show for 3 seconds
            }
        };
    </script>
</body>
</html>