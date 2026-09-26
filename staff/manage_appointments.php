<?php
require __DIR__ . '/../config.php';
require __DIR__ . '/../includes/staff_guard.php';

// Get all appointments with student and staff names
$result = mysqli_query($conn, "
    SELECT a.id, a.appointment_date, a.appointment_time, a.purpose, a.status,
           s.name AS student_name, t.name AS staff_name
    FROM appointments a
    JOIN users s ON a.student_id = s.id
    JOIN users t ON a.staff_id = t.id
    ORDER BY a.appointment_date, a.appointment_time
");

// Status options
$statuses = array("Pending", "Approved", "Completed", "Cancelled");

include __DIR__ . '/../includes/header.php';
?>

<div class="page-head">
    <h1 class="page-title">Manage Appointments</h1>
    <p class="page-subtitle">Update appointment status or remove appointments.</p>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-info"><?php echo htmlspecialchars($_GET['msg']); ?></div>
<?php endif; ?>

<div class="card table-card">
<div class="table-responsive">
<table class="table mb-0">
    <thead>
        <tr>
            <th>Student</th>
            <th>Staff</th>
            <th>Date</th>
            <th>Time</th>
            <th>Purpose</th>
            <th>Status</th>
            <th>Update Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['staff_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['appointment_time']); ?></td>
                    <td><?php echo htmlspecialchars($row['purpose']); ?></td>
                    <td><span class="status-pill <?php echo status_class($row['status']); ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
                    <td>
                        <form method="POST" action="update_status.php" class="d-flex">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <select name="status" class="form-select form-select-sm me-2">
                                <?php foreach ($statuses as $s): ?>
                                    <option value="<?php echo $s; ?>" <?php echo ($s === $row['status']) ? 'selected' : ''; ?>>
                                        <?php echo $s; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </form>
                    </td>
                    <td>
                        <a href="staff_delete_appointment.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this appointment?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center">No appointments found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
