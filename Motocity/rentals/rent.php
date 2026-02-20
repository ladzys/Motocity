<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Rental.php';
require_once __DIR__ . '/../classes/Motorbike.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Validator.php';

Auth::requireLogin();
require __DIR__ . '/../templates/header.php';

$actor = Auth::user();
$isAdmin = ($actor['user_type'] ?? '') === 'ADMIN';
$errors = [];

$targetUserId = $actor['id'];
if ($isAdmin && isset($_GET['user_id']) && Validator::positiveInt($_GET['user_id'])) {
    $targetUserId = (int)$_GET['user_id'];
}
if ($isAdmin && isset($_POST['user_id']) && Validator::positiveInt($_POST['user_id'])) {
    $targetUserId = (int)$_POST['user_id'];
}

$targetUser = User::findById((int)$targetUserId);
if (!$targetUser) {
    $errors[] = 'Target user not found.';
}

$availableBikes = Motorbike::available();
$bikeId = $_GET['bike_id'] ?? '';
$startInput = date('Y-m-d\TH:i');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bikeId = $_POST['bike_id'] ?? '';
    $startInput = $_POST['start_time'] ?? '';

    if (!Validator::positiveInt($bikeId)) $errors[] = 'Please select a valid motorbike.';
    if (!Validator::dateTime($startInput)) $errors[] = 'Please provide a valid start date/time.';

    if (!$isAdmin) {
        $targetUserId = (int)$actor['id'];
    }

    if (!$errors) {
        try {
            $start = date('Y-m-d H:i:s', strtotime($startInput));
            $result = Rental::rent((int)$targetUserId, (int)$bikeId, $start);
            Auth::flash('success', 'Rental successful. Start: ' . $result['start_time'] . ' | Cost per hour: $' . number_format((float)$result['cost_per_hour'], 2));

            if ($isAdmin) {
                header('Location: ../admin/rentals.php');
            } else {
                header('Location: ../user/my_current.php');
            }
            exit;
        } catch (Throwable $e) {
            $errors[] = $e->getMessage();
        }
    }
}
?>

<h2>Rent Motorbike</h2>

<?php foreach ($errors as $e): ?>
<p class="error"><?= htmlspecialchars($e) ?></p>
<?php endforeach; ?>

<?php if ($targetUser): ?>
<?php if ($isAdmin): ?>
<p><strong>Renting for user:</strong> <?= htmlspecialchars($targetUser['first_name']) ?> <?= htmlspecialchars($targetUser['last_name']) ?> (<?= htmlspecialchars($targetUser['email']) ?>)</p>
<?php endif; ?>

<form method="post" class="stack">
<?php if ($isAdmin): ?>
<input type="hidden" name="user_id" value="<?= (int)$targetUser['id'] ?>">
<?php endif; ?>
<div>
Motorbike:<br>
<select name="bike_id" required>
<option value="">-- Select Motorbike --</option>
<?php foreach ($availableBikes as $b): ?>
<option value="<?= (int)$b['id'] ?>" <?= (string)$bikeId === (string)$b['id'] ? 'selected' : '' ?>>
<?= htmlspecialchars($b['code']) ?> | <?= htmlspecialchars($b['renting_location']) ?> | $<?= number_format((float)$b['cost_per_hour'], 2) ?>/hr
</option>
<?php endforeach; ?>
</select>
</div>
<div>
Start date/time:<br>
<input type="datetime-local" name="start_time" value="<?= htmlspecialchars($startInput) ?>" required>
</div>
<button type="submit">Confirm Rent</button>
</form>
<?php endif; ?>

<?php require __DIR__ . '/../templates/footer.php'; ?>
