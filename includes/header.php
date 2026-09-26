<?php
// Reusable header + top navbar
// Note: config.php (which starts the session) must be required before this file

$loggedIn = isset($_SESSION['user_id']);
$role = $loggedIn ? $_SESSION['role'] : '';
$current = basename($_SERVER['SCRIPT_NAME']);

// View helper: map appointment status to a pill CSS class
function status_class($status) {
    switch ($status) {
        case 'Approved':  return 'status-approved';
        case 'Completed': return 'status-completed';
        case 'Cancelled': return 'status-cancelled';
        default:          return 'status-pending';
    }
}

// Helper: mark active navbar link
function nav_active($file, $current) {
    return ($file === $current) ? 'active' : '';
}

$home = $loggedIn ? ($role === 'staff' ? 'staff/staff_dashboard.php' : 'student/student_dashboard.php') : 'index.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Consultation Appointment System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom design -->
    <link href="<?php echo $base_url; ?>css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg app-navbar">
    <div class="container">
        <a class="navbar-brand" href="<?php echo $base_url . $home; ?>">Consultation System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto">
                <?php if ($loggedIn && $role === 'student'): ?>
                    <li class="nav-item"><a class="nav-link <?php echo nav_active('student_dashboard.php', $current); ?>" href="<?php echo $base_url; ?>student/student_dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo nav_active('add_appointment.php', $current); ?>" href="<?php echo $base_url; ?>student/add_appointment.php">Add Appointment</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo nav_active('my_appointments.php', $current); ?>" href="<?php echo $base_url; ?>student/my_appointments.php">My Appointments</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo $base_url; ?>logout.php">Logout</a></li>
                <?php elseif ($loggedIn && $role === 'staff'): ?>
                    <li class="nav-item"><a class="nav-link <?php echo nav_active('staff_dashboard.php', $current); ?>" href="<?php echo $base_url; ?>staff/staff_dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo nav_active('manage_appointments.php', $current); ?>" href="<?php echo $base_url; ?>staff/manage_appointments.php">Manage Appointments</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo $base_url; ?>logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link <?php echo nav_active('index.php', $current); ?>" href="<?php echo $base_url; ?>index.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo nav_active('register.php', $current); ?>" href="<?php echo $base_url; ?>register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<script>
    // Base URL for JavaScript (used by AJAX requests) - matches PHP $base_url
    var BASE_URL = "<?php echo $base_url; ?>";
</script>

<div class="container page-container">
