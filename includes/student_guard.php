<?php
// Allow only logged-in Student
// Require config.php (starts session) before this file

// Not logged in -> go to login
if (!isset($_SESSION['user_id'])) {
    header("Location: " . $base_url . "index.php");
    exit;
}

// Logged in but not a student -> send to staff dashboard
if ($_SESSION['role'] !== 'student') {
    header("Location: " . $base_url . "staff/staff_dashboard.php");
    exit;
}
