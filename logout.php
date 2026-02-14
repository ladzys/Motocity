<?php
require_once 'config.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';

$user = new User();
$user->logout();

header("Location: index.php");
exit();
