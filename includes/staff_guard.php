<?php
// Allow only logged-in Staff
// Require config.php (starts session) before this file

// Not logged in -> go to login
if (!isset($_SESSION['user_id'])) {
    header("Location: " . $base_url . "index.php");
    exit;
}

// Logged in but not staff -> send to student dashboard
if ($_SESSION['role'] !== 'staff') {
    header("Location: " . $base_url . "student/student_dashboard.php");
    exit;
}
