<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Rental.php';

Auth::requireLogin();
require __DIR__ . '/../templates/header.php';
$user = Auth::user();

$rentals = Rental::currentByUser($user['id']);
?>

<h2>My Current Rentals</h2>

<table>
<tr><th>Code</th><th>Start</th><th>Return</th></tr>

<?php foreach($rentals as $r): ?>
<tr>
<td><?= htmlspecialchars($r['code']) ?></td>
<td><?= htmlspecialchars($r['start_time']) ?></td>
<td><a href="../rentals/return.php?rental_id=<?= (int)$r['id'] ?>">Return</a></td>
</tr>
<?php endforeach; ?>
</table>

<?php if (!$rentals): ?>
<p class="empty">You have no ongoing rentals.</p>
<?php endif; ?>

<?php require __DIR__ . '/../templates/footer.php'; ?>