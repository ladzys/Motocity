<?php
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Validator.php';

Auth::start();
require __DIR__ . '/../templates/header.php';

$errors = [];
$first = '';
$last = '';
$phone = '';
$email = '';
$type = 'USER';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $first = trim($_POST['first_name'] ?? '');
    $last  = trim($_POST['last_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $type  = $_POST['user_type'] ?? 'USER';

    if (!Validator::required($first)) $errors[] = "First name required";
    if (!Validator::required($last)) $errors[] = "Last name required";
    if (!Validator::phone($phone)) $errors[] = "Invalid phone";
    if (!Validator::email($email)) $errors[] = "Invalid email";
    if (strlen($pass) < 6) $errors[] = "Password must be at least 6 characters";
    if (!in_array($type, ['USER', 'ADMIN'], true)) $errors[] = "Invalid user type";

    if (Validator::email($email) && User::findByEmail($email)) $errors[] = "Email already exists";

    if (!$errors) {
        User::create([
            'first_name'=>$first,
            'last_name'=>$last,
            'phone'=>$phone,
            'email'=>$email,
            'password'=>$pass,
            'user_type'=>$type
        ]);
        header("Location: login.php");
        exit;
    }
}
?>

<h2>Register</h2>

<?php foreach($errors as $e): ?>
<p class="error"><?= htmlspecialchars($e) ?></p>
<?php endforeach; ?>

<form method="post" class="stack">
<div>
First Name:<br>
<input name="first_name" value="<?= htmlspecialchars($first) ?>">
</div>
<div>
Last Name:<br>
<input name="last_name" value="<?= htmlspecialchars($last) ?>">
</div>
<div>
Phone:<br>
<input name="phone" value="<?= htmlspecialchars($phone) ?>">
</div>
<div>
Email:<br>
<input name="email" value="<?= htmlspecialchars($email) ?>">
</div>
<div>
Password:<br>
<input type="password" name="password">
</div>
<div>
Type:<br>
<select name="user_type">
    <option value="USER" <?= $type === 'USER' ? 'selected' : '' ?>>User</option>
    <option value="ADMIN" <?= $type === 'ADMIN' ? 'selected' : '' ?>>Admin</option>
</select>
</div>
<button type="submit">Register</button>
</form>

<?php require __DIR__ . '/../templates/footer.php'; ?>