<?php
include 'db.php';

$blood_groups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];

$sql = "SELECT blood_group, COUNT(*) AS total_donors FROM donors GROUP BY blood_group";
$result = $conn->query($sql);

// Store query results in an associative array
$donor_data = [];
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $donor_data[$row["blood_group"]] = $row["total_donors"];
}

// Prepare data for the chart
$chart_labels = json_encode($blood_groups);
$chart_values = json_encode(array_map(function($bg) use ($donor_data) {
    return isset($donor_data[$bg]) ? $donor_data[$bg] : 0;
}, $blood_groups));

$conn = null; // Close the database connection
?>

<!DOCTYPE html>
<html>
<head>
    <title>Blood Donor Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        flex-direction: column;
        padding: 20px;
    }
    table { width: 50%; margin: auto; border-collapse: collapse; margin-bottom: 20px; }
    th, td { border: 1px solid black; padding: 10px; }
    th { background-color: #f2f2f2; }
    canvas { max-width: 500px; margin: auto; }
    </style>
</head>
<body>
    <h2>Blood Donor Report</h2>

    <!-- Table -->
    <table>
        <tr>
            <th>Blood Group</th>
            <th>Total Donors</th>
        </tr>
        <?php
        foreach ($blood_groups as $bg) {
            $total_donors = isset($donor_data[$bg]) ? $donor_data[$bg] : 0;
            echo "<tr><td>" . htmlspecialchars($bg) . "</td><td>" . htmlspecialchars($total_donors) . "</td></tr>";
        }
        ?>
    </table>

    <!-- Pie Chart -->
    <canvas id="bloodChart"></canvas>

    <script>
        var ctx = document.getElementById('bloodChart').getContext('2d');
        var bloodChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo $chart_labels; ?>,
                datasets: [{
                    data: <?php echo $chart_values; ?>,
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                        '#9966FF', '#FF9F40', '#8BC34A', '#E91E63'
                    ]
                }]
            }
        });
    </script>
    <p>Pie graph of all donors in our website.</p>

</body>
</html>
