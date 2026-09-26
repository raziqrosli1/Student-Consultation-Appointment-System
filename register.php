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

// Handle register form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Server-side validation
    if ($name === "" || $email === "" || $password === "" || $role === "") {

        $message = "Please fill in all required fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Invalid email format.";

    } elseif (!in_array($role, ['student', 'staff'])) {

        $message = "Invalid role selected.";

    } else {

        // Check if email already exists
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {

            $message = "Email is already registered.";

        } else {

            // Hash password
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $insert = mysqli_prepare(
                $conn,
                "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $insert,
                "ssss",
                $name,
                $email,
                $hash,
                $role
            );

            if (mysqli_stmt_execute($insert)) {

                $message = "Registration successful. You can now login.";

            } else {

                $message = "Something went wrong. Please try again.";
            }

            mysqli_stmt_close($insert);
        }

        mysqli_stmt_close($stmt);
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="auth-card">

    <h1 class="auth-title">Create account</h1>

    <p class="auth-sub">
        Register as a student or staff member.
    </p>

    <?php if ($message !== ""): ?>
        <div class="alert alert-info">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="register.php" onsubmit="return validateRegister(this);">

        <!-- Name -->
        <div class="mb-3">
            <label class="form-label">Name</label>

            <input
                type="text"
                name="name"
                class="form-control"
                placeholder="Your full name"
                required
            >
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label class="form-label">Email</label>

            <input
                type="email"
                name="email"
                class="form-control"
                placeholder="you@example.com"
                required
            >
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label class="form-label">Password</label>

            <input
                type="password"
                name="password"
                class="form-control"
                placeholder="Create a password"
                required
            >
        </div>

        <!-- Role -->
        <div class="mb-3">
            <label class="form-label">Register As</label>

            <select name="role" class="form-select" required>
                <option value="">-- Select Role --</option>
                <option value="student">Student</option>
                <option value="staff">Staff</option>
            </select>
        </div>

        <!-- Register Button -->
        <button type="submit" class="btn btn-primary w-100">
            Register
        </button>

    </form>

    <p class="auth-alt">
        Already have an account?
        <a href="index.php">Login</a>
    </p>

</div>

<?php include __DIR__ . '/includes/footer.php'; ?>