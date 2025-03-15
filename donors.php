<?php
// Include the database connection
include('db.php'); // Assuming db.php is in the same directory

// Function to fetch donors based on the filter criteria
function getDonors($state, $city, $blood_group, $conn) {
    // Prepare the SQL query with placeholders
    $sql = "SELECT * FROM donors WHERE state = :state AND city = :city AND blood_group = :blood_group";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    
    // Bind the input parameters to the query
    $stmt->bindParam(':state', $state);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':blood_group', $blood_group);
    
    // Execute the query
    $stmt->execute();
    
    // Return the results
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Initialize an empty array to hold donor data
$donors = [];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $state = $_POST["state"];
    $city = $_POST["city"];
    $blood_group = $_POST["blood_group"];
    
    // Call the function to fetch donor details
    $donors = getDonors($state, $city, $blood_group, $conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Donors</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="donors.css?v=<?php echo time(); ?>">
</head>
<body>
<div class="box">
    <h1>Search for Donors</h1>
    
    <!-- Form to get the filter criteria -->
    <form method="POST" action="">
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
                    <option value ="DD">Daman and Diu</option>
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
        
        <div class="blood_group">
            <label for="blood_group">Choose the blood group required<i class='bx bxs-heart-circle'></i></label><br>
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
        
        <button class="btn1" type="submit">Search</button>

    </form>
    <button class="btn" onclick="window.location.href='home.php'">Go back to home</button>
</div>

<?php if (!empty($donors)): ?>
    <table>
        <tr>
            <th>S.No</th>
            <th>Name</th>
            <th>State</th>
            <th>City</th>
            <th>Blood Group</th>
            <th>Contact</th>
            <th>Email</th>
        </tr>
        <?php 
        $sno = 1; // Initialize serial number
        foreach ($donors as $row): ?>
            <tr>
                <td><?php echo $sno++; ?></td>
                <td><?php echo htmlspecialchars($row["name"]); ?></td>
                <td><?php echo htmlspecialchars($row["state"]); ?></td>
                <td><?php echo htmlspecialchars($row["city"]); ?></td>
                <td><?php echo htmlspecialchars($row["blood_group"]); ?></td>
                <td><?php echo htmlspecialchars($row["phone"]); ?></td>
                <td><?php echo htmlspecialchars($row["email"]); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
    <p>Sorry to inform you that no donor found with these details.</p>
<?php endif; ?>

</body>
</html>