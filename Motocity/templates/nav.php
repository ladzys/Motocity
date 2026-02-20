<?php
$user = Auth::user();
?>

<nav class="main-nav">
<a href="/ISIT307/Motocity/index.php">Home</a>

<?php if ($user): ?>

    <?php if ($user['user_type'] === 'ADMIN'): ?>
        <a href="/ISIT307/Motocity/admin/index.php">Admin Dashboard</a>
        <a href="/ISIT307/Motocity/admin/motorbikes.php">Motorbikes</a>
        <a href="/ISIT307/Motocity/admin/rentals.php">Current Rentals</a>
        <a href="/ISIT307/Motocity/admin/users.php">Users</a>
        <a href="/ISIT307/Motocity/motorbikes/search.php">Search Bikes</a>
    <?php else: ?>
        <a href="/ISIT307/Motocity/user/index.php">Dashboard</a>
        <a href="/ISIT307/Motocity/user/available.php">Available Bikes</a>
        <a href="/ISIT307/Motocity/user/my_current.php">My Current</a>
        <a href="/ISIT307/Motocity/user/my_history.php">My History</a>
        <a href="/ISIT307/Motocity/motorbikes/search.php">Search</a>
    <?php endif; ?>

    <a href="/ISIT307/Motocity/auth/logout.php">Logout</a>

<?php else: ?>

    <a href="/ISIT307/Motocity/auth/login.php">Login</a>
    <a href="/ISIT307/Motocity/auth/register.php">Register</a>

<?php endif; ?>
</nav>