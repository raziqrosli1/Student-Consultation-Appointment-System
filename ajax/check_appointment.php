<?php
// AJAX endpoint: check if appointment slot is already booked
require '../config.php';

// Only logged-in users may use this
if (!isset($_SESSION['user_id'])) {
    echo "Not allowed.";
    exit;
}

$staff_id = isset($_GET['staff_id']) ? trim($_GET['staff_id']) : "";
$date = isset($_GET['date']) ? trim($_GET['date']) : "";
$time = isset($_GET['time']) ? trim($_GET['time']) : "";

// Need all three values
if ($staff_id === "" || $date === "" || $time === "") {
    echo "Please select staff, date and time.";
    exit;
}

// Check the slot using a prepared statement
$stmt = mysqli_prepare($conn, "SELECT id FROM appointments WHERE staff_id = ? AND appointment_date = ? AND appointment_time = ?");
mysqli_stmt_bind_param($stmt, "iss", $staff_id, $date, $time);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    echo "Appointment slot is already booked.";
} else {
    echo "Appointment slot is available.";
}
?>
