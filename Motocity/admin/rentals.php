<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Rental.php';

Auth::requireAdmin();

require __DIR__ . '/../templates/header.php';

$rentals = Rental::allCurrent();
?>

<h2>Current Rentals</h2>

<table>
<tr>
<th>User</th>
<th>Bike</th>
<th>Start Time</th>
<th>Return</th>
</tr>

<?php foreach($rentals as $r): ?>
<tr>
<td><?= htmlspecialchars($r['first_name']) ?> <?= htmlspecialchars($r['last_name']) ?></td>
<td><?= htmlspecialchars($r['code']) ?></td>
<td><?= htmlspecialchars($r['start_time']) ?></td>
<td>
<a href="../rentals/return.php?rental_id=<?= (int)$r['id'] ?>">Return</a>
</td>
</tr>
<?php endforeach; ?>
</table>

<?php require __DIR__ . '/../templates/footer.php'; ?>