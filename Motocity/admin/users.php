<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Rental.php';

Auth::requireAdmin();
require __DIR__ . '/../templates/header.php';

$q = $_GET['q'] ?? '';
$users = $q ? User::search($q) : User::all();

$currentRenters = Rental::usersCurrentlyRenting();
?>

<h2>All Users</h2>

<form method="get">
Search: <input name="q" value="<?= htmlspecialchars($q) ?>">
<button>Search</button>
</form>

<table>
<tr>
<th>ID</th>
<th>Name</th>
<th>Surname</th>
<th>Email</th>
<th>Phone</th>
<th>Type</th>
<th>Action</th>
</tr>

<?php foreach($users as $u): ?>
<tr>
<td><?= (int)$u['id'] ?></td>
<td><?= htmlspecialchars($u['first_name']) ?></td>
<td><?= htmlspecialchars($u['last_name']) ?></td>
<td><?= htmlspecialchars($u['email']) ?></td>
<td><?= htmlspecialchars($u['phone']) ?></td>
<td><?= htmlspecialchars($u['user_type']) ?></td>
<td><a href="rent_for_user.php?user_id=<?= (int)$u['id'] ?>">Rent Motorbike</a></td>
</tr>
<?php endforeach; ?>
</table>

<hr>

<h2>Users Currently Renting</h2>

<table>
<tr>
<th>ID</th>
<th>Name</th>
<th>Surname</th>
<th>Email</th>
<th>Phone</th>
<th>Type</th>
</tr>

<?php foreach($currentRenters as $u): ?>
<tr>
<td><?= (int)$u['id'] ?></td>
<td><?= htmlspecialchars($u['first_name']) ?></td>
<td><?= htmlspecialchars($u['last_name']) ?></td>
<td><?= htmlspecialchars($u['email']) ?></td>
<td><?= htmlspecialchars($u['phone']) ?></td>
<td><?= htmlspecialchars($u['user_type']) ?></td>
</tr>
<?php endforeach; ?>
</table>

<?php require __DIR__ . '/../templates/footer.php'; ?>