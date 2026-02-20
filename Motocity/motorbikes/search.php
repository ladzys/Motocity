<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Motorbike.php';

Auth::requireLogin();
require __DIR__ . '/../templates/header.php';

$user = Auth::user();
$availableOnly = ($user['user_type'] ?? 'USER') !== 'ADMIN';

$filters = [
    'code' => trim($_GET['code'] ?? ''),
    'renting_location' => trim($_GET['renting_location'] ?? ''),
    'description' => trim($_GET['description'] ?? '')
];

$results = Motorbike::search($filters, $availableOnly);
?>

<h2>Search Motorbikes</h2>

<form method="get" class="stack">
<div>
Code: <input name="code" value="<?= htmlspecialchars($filters['code']) ?>">
</div>
<div>
Location: <input name="renting_location" value="<?= htmlspecialchars($filters['renting_location']) ?>">
</div>
<div>
Description: <input name="description" value="<?= htmlspecialchars($filters['description']) ?>">
</div>
<button>Search</button>
</form>

<table>
<tr>
<th>Code</th>
<th>Location</th>
<th>Description</th>
<th>Cost/hr</th>
</tr>

<?php foreach($results as $b): ?>
<tr>
<td><?= htmlspecialchars($b['code']) ?></td>
<td><?= htmlspecialchars($b['renting_location']) ?></td>
<td><?= htmlspecialchars($b['description']) ?></td>
<td>$<?= number_format((float)$b['cost_per_hour'], 2) ?></td>
</tr>
<?php endforeach; ?>
</table>

<?php if (!$results): ?>
<p class="empty">No motorbikes found.</p>
<?php endif; ?>

<?php require __DIR__ . '/../templates/footer.php'; ?>