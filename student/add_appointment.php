<?php
require __DIR__ . '/../config.php';
require __DIR__ . '/../includes/student_guard.php';

$student_id = $_SESSION['user_id'];
$message = "";

// Handle add appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff_id = isset($_POST['staff_id']) ? (int) $_POST['staff_id'] : 0;
    $date = trim($_POST['appointment_date']);
    $time = trim($_POST['appointment_time']);
    $purpose = trim($_POST['purpose']);

    // Check date is valid (YYYY-MM-DD)
    $dateParts = explode("-", $date);
    $dateValid = (count($dateParts) === 3 && checkdate((int)$dateParts[1], (int)$dateParts[2], (int)$dateParts[0]));

    // Check time is valid (HH:MM or HH:MM:SS)
    $timeValid = preg_match('/^([01]\d|2[0-3]):[0-5]\d(:[0-5]\d)?$/', $time) === 1;

    // Check staff exists and role is really 'staff'
    $staffValid = false;
    if ($staff_id > 0) {
        $staffCheck = mysqli_prepare($conn, "SELECT id FROM users WHERE id = ? AND role = 'staff'");
        mysqli_stmt_bind_param($staffCheck, "i", $staff_id);
        mysqli_stmt_execute($staffCheck);
        mysqli_stmt_store_result($staffCheck);
        $staffValid = (mysqli_stmt_num_rows($staffCheck) > 0);
    }

    // Server-side validation
    if ($staff_id <= 0 || $date === "" || $time === "" || $purpose === "") {
        $message = "Please fill in all required fields.";
    } elseif (!$dateValid) {
        $message = "Invalid date.";
    } elseif (!$timeValid) {
        $message = "Invalid time.";
    } elseif (!$staffValid) {
        $message = "Invalid staff selected.";
    } else {
        // Check if the slot is already booked for that staff
        $check = mysqli_prepare($conn, "SELECT id FROM appointments WHERE staff_id = ? AND appointment_date = ? AND appointment_time = ?");
        mysqli_stmt_bind_param($check, "iss", $staff_id, $date, $time);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $message = "Appointment slot is already booked.";
        } else {
            // Insert new appointment (status defaults to Pending)
            $insert = mysqli_prepare($conn, "INSERT INTO appointments (student_id, staff_id, appointment_date, appointment_time, purpose) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($insert, "iisss", $student_id, $staff_id, $date, $time, $purpose);

            if (mysqli_stmt_execute($insert)) {
                $message = "Appointment successfully added.";
            } else {
                $message = "Something went wrong. Please try again.";
            }
        }
    }
}

// Get staff list for dropdown
$staffList = mysqli_query($conn, "SELECT id, name FROM users WHERE role = 'staff' ORDER BY name");

include __DIR__ . '/../includes/header.php';
?>

<div class="page-head">
    <h1 class="page-title">Add Appointment</h1>
    <p class="page-subtitle">Book a consultation with a staff member.</p>
</div>

<?php if ($message !== ""): ?>
    <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<div class="card form-card">
    <div class="card-body">
        <form method="POST" action="add_appointment.php" onsubmit="return validateAppointment(this);">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Staff</label>
                    <select name="staff_id" id="staff_id" class="form-select" onchange="checkSlot();">
                        <option value="">-- Select Staff --</option>
                        <?php while ($staff = mysqli_fetch_assoc($staffList)): ?>
                            <option value="<?php echo $staff['id']; ?>"><?php echo htmlspecialchars($staff['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Date</label>
                    <input type="date" name="appointment_date" id="appointment_date" class="form-control" onchange="checkSlot();">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Time</label>
                    <input type="time" name="appointment_time" id="appointment_time" class="form-control" onchange="checkSlot();">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Purpose</label>
                    <input type="text" name="purpose" id="purpose" class="form-control">
                </div>
            </div>

            <!-- AJAX availability message will be shown here -->
            <div id="slot_status" class="mt-3"></div>

            <div class="form-actions mt-4">
                <button type="submit" class="btn btn-primary">Submit Appointment</button>
                <a href="student_dashboard.php" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
