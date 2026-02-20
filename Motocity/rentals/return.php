<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Rental.php';
require_once __DIR__ . '/../classes/Validator.php';

Auth::requireLogin();
require __DIR__ . '/../templates/header.php';

$actor = Auth::user();
$isAdmin = ($actor['user_type'] ?? '') === 'ADMIN';
$errors = [];

$rentalIdRaw = $_GET['rental_id'] ?? $_POST['rental_id'] ?? '';
if (!Validator::positiveInt($rentalIdRaw)) {
    $errors[] = 'Invalid rental id.';
}

$rental = null;
if (!$errors) {
    $rental = Rental::findOngoingById((int)$rentalIdRaw);
    if (!$rental) {
        $errors[] = 'Ongoing rental not found.';
    } elseif (!$isAdmin && (int)$rental['user_id'] !== (int)$actor['id']) {
        $errors[] = 'You are not allowed to return this rental.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$errors) {
    try {
        $result = Rental::returnBike((int)$rentalIdRaw, (int)$actor['id'], $isAdmin);
        Auth::flash('success', 'Return successful. End: ' . $result['end_time'] . ' | Hours charged: ' . $result['hours'] . ' | Total cost: $' . number_format((float)$result['total_cost'], 2));

        if ($isAdmin) {
            header('Location: ../admin/rentals.php');
        } else {
            header('Location: ../user/my_history.php');
        }
        exit;
    } catch (Throwable $e) {
        $errors[] = $e->getMessage();
    }
}
?>

<h2>Return Motorbike</h2>

<?php foreach ($errors as $e): ?>
<p class="error"><?= htmlspecialchars($e) ?></p>
<?php endforeach; ?>

<?php if ($rental && !$errors): ?>
<p><strong>Bike:</strong> <?= htmlspecialchars($rental['code']) ?></p>
<p><strong>Rented by:</strong> <?= htmlspecialchars($rental['first_name']) ?> <?= htmlspecialchars($rental['last_name']) ?></p>
<p><strong>Start time:</strong> <?= htmlspecialchars($rental['start_time']) ?></p>
<p><strong>Cost per hour:</strong> $<?= number_format((float)$rental['cost_per_hour'], 2) ?></p>

<form method="post">
<input type="hidden" name="rental_id" value="<?= (int)$rental['id'] ?>">
<button type="submit">Confirm Return</button>
</form>
<?php endif; ?>

<?php require __DIR__ . '/../templates/footer.php'; ?>
