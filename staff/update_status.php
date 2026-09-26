<?php
require __DIR__ . '/../config.php';
require __DIR__ . '/../includes/staff_guard.php';

// Allowed status values
$statuses = array("Pending", "Approved", "Completed", "Cancelled");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $status = $_POST['status'];

    // Server-side validation: status must be valid
    if ($id > 0 && in_array($status, $statuses)) {
        $stmt = mysqli_prepare($conn, "UPDATE appointments SET status = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "si", $status, $id);
        mysqli_stmt_execute($stmt);
        header("Location: manage_appointments.php?msg=Status updated.");
        exit;
    }
}

// If invalid, go back
header("Location: manage_appointments.php?msg=Invalid request.");
exit;
?>
