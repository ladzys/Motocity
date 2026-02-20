<?php
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Auth.php';

Auth::start();
require __DIR__ . '/../templates/header.php';
$error = "";
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    $user = User::findByEmail($email);

    if ($user && password_verify($pass, $user['password_hash'])) {
        Auth::login($user);

        if ($user['user_type'] === 'ADMIN') {
            header("Location: ../admin/index.php");
        } else {
            header("Location: ../user/index.php");
        }
        exit;
    } else {
        $error = "Invalid login";
    }
}
?>

<h2>Login</h2>
<?php if ($error): ?>
<p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post" class="stack">
<div>
Email:<br>
<input name="email" value="<?= htmlspecialchars($email) ?>">
</div>
<div>
Password:<br>
<input type="password" name="password">
</div>
<button type="submit">Login</button>
</form>

<?php require __DIR__ . '/../templates/footer.php'; ?>