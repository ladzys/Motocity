<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Motorbike.php';
require_once __DIR__ . '/../classes/Validator.php';

Auth::requireAdmin();
require __DIR__ . '/../templates/header.php';

$id = isset($_GET['id']) && Validator::positiveInt($_GET['id']) ? (int)$_GET['id'] : null;
$bike = $id ? Motorbike::find($id) : null;
$errors = [];

if ($id && !$bike) {
    $errors[] = 'Motorbike not found.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = [
        'code'=>trim($_POST['code'] ?? ''),
        'renting_location'=>trim($_POST['renting_location'] ?? ''),
        'description'=>trim($_POST['description'] ?? ''),
        'cost_per_hour'=>trim($_POST['cost_per_hour'] ?? '')
    ];

    if (!Validator::code($data['code'])) $errors[] = 'Code must be 3-30 letters/numbers/hyphens.';
    if (!Validator::required($data['renting_location'])) $errors[] = 'Location is required.';
    if (!Validator::required($data['description'])) $errors[] = 'Description is required.';
    if (!Validator::money($data['cost_per_hour'])) $errors[] = 'Cost per hour must be a valid non-negative number.';

    if (!$errors) {
        if ($id) {
            Motorbike::update($id, $data);
        } else {
            Motorbike::create($data);
        }

        header("Location: motorbikes.php");
        exit;
    }

    $bike = array_merge($bike ?? [], $data);
}
?>

<h2><?= $id ? "Edit" : "Add" ?> Motorbike</h2>

<?php foreach($errors as $e): ?>
<p class="error"><?= htmlspecialchars($e) ?></p>
<?php endforeach; ?>

<form method="post" class="stack">
<div>
Code:<br>
<input name="code" value="<?= htmlspecialchars($bike['code'] ?? '') ?>">
</div>
<div>
Location:<br>
<input name="renting_location" value="<?= htmlspecialchars($bike['renting_location'] ?? '') ?>">
</div>
<div>
Description:<br>
<input name="description" value="<?= htmlspecialchars($bike['description'] ?? '') ?>">
</div>
<div>
Cost/hr:<br>
<input name="cost_per_hour" value="<?= htmlspecialchars((string)($bike['cost_per_hour'] ?? '')) ?>">
</div>
<button type="submit">Save</button>
</form>

<?php require __DIR__ . '/../templates/footer.php'; ?>