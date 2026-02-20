<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Motorbike.php';
require_once __DIR__ . '/../classes/Rental.php';

Auth::requireAdmin();
require __DIR__ . '/../templates/header.php';

$bikes = Motorbike::all();
$ongoingRentals = Rental::allCurrent();
$rentedBikeIds = [];
foreach ($ongoingRentals as $r) {
	$rentedBikeIds[(int)$r['motorbike_id']] = true;
}
?>

<h2>Motorbikes</h2>
<p><a class="action-link" href="motorbike_form.php">Add New</a></p>

<table>
<tr>
<th>Code</th><th>Location</th><th>Description</th><th>Cost/hr</th><th>Availability</th><th>Edit</th>
</tr>

<?php foreach($bikes as $b): ?>
<tr>
<td><?= htmlspecialchars($b['code']) ?></td>
<td><?= htmlspecialchars($b['renting_location']) ?></td>
<td><?= htmlspecialchars($b['description']) ?></td>
<td>$<?= number_format((float)$b['cost_per_hour'], 2) ?></td>
<td>
<?= (int)$b['is_active'] !== 1 ? 'Inactive' : (isset($rentedBikeIds[(int)$b['id']]) ? 'Currently Rented' : 'Available') ?>
</td>
<td><a href="motorbike_form.php?id=<?= (int)$b['id'] ?>">Edit</a></td>
</tr>
<?php endforeach; ?>
</table>

<?php require __DIR__ . '/../templates/footer.php'; ?>