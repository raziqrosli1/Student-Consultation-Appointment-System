<?php
require __DIR__ . '/../config.php';
require __DIR__ . '/../includes/student_guard.php';

$student_id = $_SESSION['user_id'];

// Get own appointments with staff name
$stmt = mysqli_prepare($conn, "
    SELECT a.id, a.appointment_date, a.appointment_time, a.purpose, a.status, t.name AS staff_name
    FROM appointments a
    JOIN users t ON a.staff_id = t.id
    WHERE a.student_id = ?
    ORDER BY a.appointment_date, a.appointment_time
");
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

include __DIR__ . '/../includes/header.php';
?>

<div class="page-head d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">My Appointments</h1>
        <p class="page-subtitle">All your consultation appointments.</p>
    </div>
    <a href="add_appointment.php" class="btn btn-primary">Add Appointment</a>
</div>

<div class="card table-card">
<div class="table-responsive">
<table class="table mb-0">
    <thead>
        <tr>
            <th>Staff</th>
            <th>Date</th>
            <th>Time</th>
            <th>Purpose</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['staff_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['appointment_time']); ?></td>
                    <td><?php echo htmlspecialchars($row['purpose']); ?></td>
                    <td><span class="status-pill <?php echo status_class($row['status']); ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
                    <td>
                        <a href="edit_appointment.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="delete_appointment.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this appointment?');">Cancel</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">No appointments found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
