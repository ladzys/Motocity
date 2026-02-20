<?php
require_once __DIR__ . '/../classes/Auth.php';
Auth::start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MotoCity</title>
    <link rel="stylesheet" href="/ISIT307/Motocity/public/css/style.css">
</head>
<body>

<div class="container">
<header class="site-header">
    <h1>MotoCity Motorbike Rental</h1>
    <?php require __DIR__ . '/nav.php'; ?>
</header>

<?php require __DIR__ . '/flash.php'; ?>

<main class="content-card">