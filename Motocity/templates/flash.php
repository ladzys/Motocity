<?php
Auth::start();
$items = $_SESSION['flash'] ?? [];
unset($_SESSION['flash']);
foreach ($items as $f) {
  $t = htmlspecialchars($f['type']);
  $m = htmlspecialchars($f['msg']);
  echo "<div class='flash {$t}'>{$m}</div>";
}