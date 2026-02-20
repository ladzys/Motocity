<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/User.php';
Auth::requireAdmin();
require __DIR__ . '/../templates/header.php';

$sessionUser = Auth::user();
$admin = User::findById((int)$sessionUser['id']);
?>

<h2>Admin Dashboard</h2>

<?php if ($admin): ?>
<h3>My Profile</h3>
<table>
<tr><th>ID</th><td><?= (int)$admin['id'] ?></td></tr>
<tr><th>Name</th><td><?= htmlspecialchars($admin['first_name']) ?></td></tr>
<tr><th>Surname</th><td><?= htmlspecialchars($admin['last_name']) ?></td></tr>
<tr><th>Phone</th><td><?= htmlspecialchars($admin['phone']) ?></td></tr>
<tr><th>Email</th><td><?= htmlspecialchars($admin['email']) ?></td></tr>
<tr><th>Type</th><td><?= htmlspecialchars($admin['user_type']) ?></td></tr>
</table>
<?php endif; ?>

<p><a class="action-link" href="motorbikes.php">Manage Motorbikes</a></p>
<p><a class="action-link" href="rentals.php">Current Rentals</a></p>
<p><a class="action-link" href="users.php">Manage Users</a></p>

<?php require __DIR__ . '/../templates/footer.php'; ?>