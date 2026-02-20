<?php
class Auth {
  public static function start(): void {
    if (date_default_timezone_get() !== 'Asia/Singapore') {
      date_default_timezone_set('Asia/Singapore');
    }
    if (session_status() === PHP_SESSION_NONE) session_start();
  }

  public static function user(): ?array {
    self::start();
    return $_SESSION['user'] ?? null;
  }

  public static function requireLogin(): void {
    self::start();
    if (!isset($_SESSION['user'])) {
      header("Location: /ISIT307/Motocity/auth/login.php");
      exit;
    }
  }

  public static function requireAdmin(): void {
    self::requireLogin();
    if ($_SESSION['user']['user_type'] !== 'ADMIN') {
      http_response_code(403);
      echo "403 Forbidden (Admin only)";
      exit;
    }
  }

  public static function login(array $userRow): void {
    self::start();
    $_SESSION['user'] = [
      'id' => $userRow['id'],
      'first_name' => $userRow['first_name'],
      'last_name' => $userRow['last_name'],
      'email' => $userRow['email'],
      'user_type' => $userRow['user_type']
    ];
  }

  public static function logout(): void {
    self::start();
    session_destroy();
  }

  public static function flash(string $type, string $msg): void {
    self::start();
    $_SESSION['flash'][] = ['type'=>$type,'msg'=>$msg];
  }
}