<?php
// booking.php

$host = "localhost";
$username = "root";
$password = "";
$database = "glowtique_db";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST["name"]));
    $phone = htmlspecialchars(trim($_POST["phone"]));

    if(isset($_POST["service"]) && is_array($_POST["service"])) {
        $services = $_POST["service"];
        $serviceList = implode(", ", $services);
    } else {
        die("<h2>❌ Please select at least one service.</h2><a href='booking.html'>← Back</a>");
    }

    $amount = floatval($_POST["amount"]);
    $date = $_POST["date"];
    $time = $_POST["time"];
    $message = htmlspecialchars(trim($_POST["message"]));

    if (!is_numeric($amount) || $amount <= 0) {
        die("<h2>❌ Invalid Amount</h2><a href='booking.html'>← Back</a>");
    }

    // -------- Slot Check --------
    $sql_check = "SELECT COUNT(*) as count FROM appointments WHERE appointment_date=? AND appointment_time=?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ss", $date, $time);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $row_check = $result_check->fetch_assoc();

    if($row_check['count'] >= 1) { // max 2 bookings per slot
        die("<h2>❌ Slot not available. Please choose another time.</h2><a href='booking.html'>← Back</a>");
    }
    // ---------------------------

    $sql = "INSERT INTO appointments (name, phone, service, amount, appointment_date, appointment_time, message)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdsss", $name, $phone, $serviceList, $amount, $date, $time, $message);

    if ($stmt->execute()) {
        echo "<h2>✅ Appointment Confirmed!</h2>";
        echo "<p>Thank you <b>$name</b>, your appointment is booked for <b>$date at $time</b>.</p>";
        echo "<p><b>Services:</b> $serviceList</p>";
        echo "<p><b>Total Amount:</b> ₹$amount</p>";
        echo "<a href='booking.html'>← Book Another</a>";
    } else {
        echo "<h2>❌ Failed to Book</h2>";
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<h2>Access Denied</h2>";
}
?>
