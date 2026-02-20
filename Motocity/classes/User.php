<?php
require_once __DIR__ . '/Database.php';

class User {
  public static function findById(int $id): ?array {
    $pdo = Database::conn();
    $st = $pdo->prepare("SELECT id, first_name,last_name,phone,email,user_type FROM users WHERE id = ?");
    $st->execute([$id]);
    $row = $st->fetch();
    return $row ?: null;
  }

  public static function findByEmail(string $email): ?array {
    $pdo = Database::conn();
    $st = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $st->execute([$email]);
    $row = $st->fetch();
    return $row ?: null;
  }

  public static function create(array $data): int {
    $pdo = Database::conn();
    $st = $pdo->prepare("
      INSERT INTO users (first_name,last_name,phone,email,password_hash,user_type)
      VALUES (?,?,?,?,?,?)
    ");
    $st->execute([
      $data['first_name'], $data['last_name'], $data['phone'], $data['email'],
      password_hash($data['password'], PASSWORD_DEFAULT),
      $data['user_type']
    ]);
    return (int)$pdo->lastInsertId();
  }

  public static function search(string $q): array {
    $pdo = Database::conn();
    $like = "%$q%";
    $st = $pdo->prepare("
      SELECT id, first_name,last_name,phone,email,user_type
      FROM users
      WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR phone LIKE ?
      ORDER BY id DESC
    ");
    $st->execute([$like,$like,$like,$like]);
    return $st->fetchAll();
  }

  public static function all(): array {
    return Database::conn()->query("
      SELECT id, first_name,last_name,phone,email,user_type
      FROM users ORDER BY id DESC
    ")->fetchAll();
  }
}