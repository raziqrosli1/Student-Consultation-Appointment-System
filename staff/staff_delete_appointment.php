<?php
require __DIR__ . '/../config.php';
require __DIR__ . '/../includes/staff_guard.php';

// Get appointment id from URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id > 0) {
    // Staff can delete any appointment
    $stmt = mysqli_prepare($conn, "DELETE FROM appointments WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
}

// Back to manage page
header("Location: manage_appointments.php?msg=Appointment deleted.");
exit;
?>
