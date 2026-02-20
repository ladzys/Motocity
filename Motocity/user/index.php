<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/User.php';
Auth::requireLogin();
require __DIR__ . '/../templates/header.php';

$sessionUser = Auth::user();
$user = User::findById((int)$sessionUser['id']);
?>

<h2>User Dashboard</h2>

<?php if ($user): ?>
<h3>My Profile</h3>
<table>
<tr><th>ID</th><td><?= (int)$user['id'] ?></td></tr>
<tr><th>Name</th><td><?= htmlspecialchars($user['first_name']) ?></td></tr>
<tr><th>Surname</th><td><?= htmlspecialchars($user['last_name']) ?></td></tr>
<tr><th>Phone</th><td><?= htmlspecialchars($user['phone']) ?></td></tr>
<tr><th>Email</th><td><?= htmlspecialchars($user['email']) ?></td></tr>
<tr><th>Type</th><td><?= htmlspecialchars($user['user_type']) ?></td></tr>
</table>
<?php endif; ?>

<p><a class="action-link" href="available.php">Available Motorbikes</a></p>
<p><a class="action-link" href="my_current.php">My Current Rentals</a></p>
<p><a class="action-link" href="my_history.php">My Rental History</a></p>
<p><a class="action-link" href="../motorbikes/search.php">Search Motorbikes</a></p>

<?php require __DIR__ . '/../templates/footer.php'; ?>