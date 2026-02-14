<?php
if (!function_exists('h')) {
    function h($value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

function renderHeader(string $title, array $links = []): void {
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($title) ?> - <?= h(SITE_NAME) ?></title>
    <link rel="stylesheet" href="<?= h((strpos($_SERVER['PHP_SELF'], '/admin/') !== false || strpos($_SERVER['PHP_SELF'], '/user/') !== false) ? '../assets/css/style.css' : 'assets/css/style.css') ?>">
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <div class="brand"><?= h(SITE_NAME) ?></div>
            <?php if (!empty($links)): ?>
            <nav class="nav-links">
                <?php foreach ($links as $link): ?>
                    <a href="<?= h($link['href']) ?>"><?= h($link['label']) ?></a>
                <?php endforeach; ?>
            </nav>
            <?php endif; ?>
        </div>
    </header>
    <?php
}

function renderFooter(): void {
    ?>
    <footer class="footer">© <?= date('Y') ?> <?= h(SITE_NAME) ?> · ISIT307 Assignment 2</footer>
</body>
</html>
    <?php
}
