<?php
require __DIR__ . '/../config.php';
require __DIR__ . '/../includes/student_guard.php';

$student_id = $_SESSION['user_id'];

// Get appointment id from URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id > 0) {
    // Delete only if this appointment belongs to the student
    $stmt = mysqli_prepare($conn, "DELETE FROM appointments WHERE id = ? AND student_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $id, $student_id);
    mysqli_stmt_execute($stmt);
}

// Back to list
header("Location: my_appointments.php");
exit;
?>
