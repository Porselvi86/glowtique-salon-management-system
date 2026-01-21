<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit;
}

$conn = new mysqli("localhost","root","","glowtique_db");
$result = $conn->query("SELECT * FROM appointments ORDER BY appointment_date ASC");

echo "<h2>All Appointments</h2>";

// --- Navigation Buttons ---
echo "<a href='report.php' style='margin-right:15px;'>📊 View Reports</a>";
echo "<a href='admin_logout.php'>🚪 Logout</a><br><br>";

echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>ID</th><th>Name</th><th>Phone</th><th>Services</th><th>Amount</th><th>Date</th><th>Time</th><th>Message</th></tr>";

if($result->num_rows>0){
    while($row=$result->fetch_assoc()){
        echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['name']}</td>
        <td>{$row['phone']}</td>
        <td>{$row['service']}</td>
        <td>₹{$row['amount']}</td>
        <td>{$row['appointment_date']}</td>
        <td>{$row['appointment_time']}</td>
        <td>{$row['message']}</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='8'>No appointments yet.</td></tr>";
}
echo "</table>";
?>
