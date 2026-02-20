<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Motorbike.php';
require_once __DIR__ . '/../classes/Rental.php';
require_once __DIR__ . '/../classes/Validator.php';

Auth::requireAdmin();
require __DIR__ . '/../templates/header.php';

$errors = [];
$userId = $_GET['user_id'] ?? $_POST['user_id'] ?? '';
if (!Validator::positiveInt($userId)) {
    $errors[] = 'Invalid user id.';
}

$user = empty($errors) ? User::findById((int)$userId) : null;
if (empty($errors) && !$user) {
    $errors[] = 'User not found.';
}

$bikeId = '';
$startInput = date('Y-m-d\TH:i');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {
    $bikeId = $_POST['bike_id'] ?? '';
    $startInput = $_POST['start_time'] ?? '';

    if (!Validator::positiveInt($bikeId)) $errors[] = 'Please choose a motorbike.';
    if (!Validator::dateTime($startInput)) $errors[] = 'Start time is invalid.';

    if (empty($errors)) {
        $start = date('Y-m-d H:i:s', strtotime($startInput));
        $result = Rental::rent((int)$userId, (int)$bikeId, $start);
        Auth::flash('success', 'Rental created for user. Start: ' . $result['start_time'] . ', Cost/hr: $' . number_format((float)$result['cost_per_hour'], 2));
        header('Location: users.php');
        exit;
    }
}

$availableBikes = Motorbike::available();
?>

<h2>Rent Motorbike for User</h2>

<?php foreach($errors as $e): ?>
<p class="error"><?= htmlspecialchars($e) ?></p>
<?php endforeach; ?>

<?php if ($user): ?>
<p><strong>User:</strong> <?= htmlspecialchars($user['first_name']) ?> <?= htmlspecialchars($user['last_name']) ?> (<?= htmlspecialchars($user['email']) ?>)</p>

<form method="post" class="stack">
<input type="hidden" name="user_id" value="<?= (int)$user['id'] ?>">
<div>
Motorbike:<br>
<select name="bike_id" required>
<option value="">-- Select --</option>
<?php foreach($availableBikes as $b): ?>
<option value="<?= (int)$b['id'] ?>" <?= (string)$bikeId === (string)$b['id'] ? 'selected' : '' ?>>
<?= htmlspecialchars($b['code']) ?> | <?= htmlspecialchars($b['renting_location']) ?> | $<?= number_format((float)$b['cost_per_hour'], 2) ?>/hr
</option>
<?php endforeach; ?>
</select>
</div>
<div>
Start Date/Time:<br>
<input type="datetime-local" name="start_time" value="<?= htmlspecialchars($startInput) ?>" required>
</div>
<button type="submit">Create Rental</button>
</form>
<?php endif; ?>

<?php require __DIR__ . '/../templates/footer.php'; ?>
