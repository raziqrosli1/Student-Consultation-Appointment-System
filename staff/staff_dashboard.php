<?php
require __DIR__ . '/../config.php';
require __DIR__ . '/../includes/staff_guard.php';

// Read all appointments (with names) for summary + recent list
$result = mysqli_query($conn, "
    SELECT a.appointment_date, a.appointment_time, a.purpose, a.status,
           s.name AS student_name, t.name AS staff_name
    FROM appointments a
    JOIN users s ON a.student_id = s.id
    JOIN users t ON a.staff_id = t.id
    ORDER BY a.appointment_date DESC, a.appointment_time DESC
");

// Build list and count by status (in PHP, no extra queries)
$appointments = array();
$total = 0; $pending = 0; $approved = 0; $completed = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $appointments[] = $row;
    $total++;
    if ($row['status'] === 'Pending')   $pending++;
    if ($row['status'] === 'Approved')  $approved++;
    if ($row['status'] === 'Completed') $completed++;
}

include __DIR__ . '/../includes/header.php';
?>

<div class="card action-card banner-card mb-4">
    <div>
        <div class="section-title">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></div>
        <p class="action-desc">Overview of all consultation appointments.</p>
    </div>
    <a href="manage_appointments.php" class="btn btn-primary">Manage Appointments</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <p class="stat-label">Total</p>
            <p class="stat-value"><?php echo $total; ?></p>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <p class="stat-label">Pending</p>
            <p class="stat-value"><?php echo $pending; ?></p>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <p class="stat-label">Approved</p>
            <p class="stat-value"><?php echo $approved; ?></p>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card">
            <p class="stat-label">Completed</p>
            <p class="stat-value"><?php echo $completed; ?></p>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-2">
    <h2 class="section-title mb-0">Recent Appointments</h2>
    <a href="manage_appointments.php" class="btn btn-secondary btn-sm">View all</a>
</div>

<div class="card table-card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Staff</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($total > 0): ?>
                    <?php foreach (array_slice($appointments, 0, 5) as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['staff_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['appointment_time']); ?></td>
                            <td><span class="status-pill <?php echo status_class($row['status']); ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center text-muted">No appointments yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
