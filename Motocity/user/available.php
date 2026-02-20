<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Motorbike.php';

Auth::requireLogin();
require __DIR__ . '/../templates/header.php';

$bikes = Motorbike::available();
?>

<h2>Available Motorbikes</h2>

<table>
<tr>
<th>Code</th><th>Location</th><th>Description</th><th>Cost/hr</th><th>Rent</th>
</tr>

<?php foreach($bikes as $b): ?>
<tr>
<td><?= htmlspecialchars($b['code']) ?></td>
<td><?= htmlspecialchars($b['renting_location']) ?></td>
<td><?= htmlspecialchars($b['description']) ?></td>
<td>$<?= number_format((float)$b['cost_per_hour'], 2) ?></td>
<td>
<a href="../rentals/rent.php?bike_id=<?= (int)$b['id'] ?>">Rent</a>
</td>
</tr>
<?php endforeach; ?>
</table>

<?php if (!$bikes): ?>
<p class="empty">No motorbikes available right now.</p>
<?php endif; ?>

<?php require __DIR__ . '/../templates/footer.php'; ?>
