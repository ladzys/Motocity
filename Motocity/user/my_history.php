<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Rental.php';

Auth::requireLogin();
require __DIR__ . '/../templates/header.php';
$user = Auth::user();

$rentals = Rental::historyByUser($user['id']);
?>

<h2>My Rental History</h2>

<table>
<tr><th>Code</th><th>Start</th><th>End</th><th>Total</th></tr>

<?php foreach($rentals as $r): ?>
<tr>
<td><?= htmlspecialchars($r['code']) ?></td>
<td><?= htmlspecialchars($r['start_time']) ?></td>
<td><?= htmlspecialchars($r['end_time']) ?></td>
<td>$<?= number_format((float)$r['total_cost'], 2) ?></td>
</tr>
<?php endforeach; ?>
</table>

<?php if (!$rentals): ?>
<p class="empty">No completed rentals yet.</p>
<?php endif; ?>

<?php require __DIR__ . '/../templates/footer.php'; ?>