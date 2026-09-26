<?php
require 'config.php';

// If already logged in, go to correct dashboard
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'staff') {
        header("Location: staff/staff_dashboard.php");
    } else {
        header("Location: student/student_dashboard.php");
    }
    exit;
}

$message = "";

// Handle login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Server-side validation
    if ($email === "" || $password === "") {
        $message = "Please fill in all required fields.";
    } else {
        // Find user by email
        $stmt = mysqli_prepare($conn, "SELECT id, name, password, role FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            // Save session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // Redirect by role
            if ($user['role'] === 'staff') {
                header("Location: staff/staff_dashboard.php");
            } else {
                header("Location: student/student_dashboard.php");
            }
            exit;
        } else {
            $message = "Invalid email or password.";
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="auth-card">
    <h1 class="auth-title">Welcome back</h1>
    <p class="auth-sub">Sign in to continue.</p>

    <?php if ($message !== ""): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php" onsubmit="return validateLogin(this);">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="you@example.com">
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter your password">
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
    <p class="auth-alt">No account? <a href="register.php">Register</a></p>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
