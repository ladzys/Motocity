<?php
require_once __DIR__ . '/Database.php';

class Motorbike {
  public static function create(array $d): int {
    $pdo = Database::conn();
    $st = $pdo->prepare("
      INSERT INTO motorbikes (code,renting_location,description,cost_per_hour,is_active)
      VALUES (?,?,?,?,1)
    ");
    $st->execute([$d['code'],$d['renting_location'],$d['description'],$d['cost_per_hour']]);
    return (int)$pdo->lastInsertId();
  }

  public static function update(int $id, array $d): void {
    $pdo = Database::conn();
    $st = $pdo->prepare("
      UPDATE motorbikes
      SET code=?, renting_location=?, description=?, cost_per_hour=?
      WHERE id=?
    ");
    $st->execute([$d['code'],$d['renting_location'],$d['description'],$d['cost_per_hour'],$id]);
  }

  public static function find(int $id): ?array {
    $st = Database::conn()->prepare("SELECT * FROM motorbikes WHERE id=?");
    $st->execute([$id]);
    $row = $st->fetch();
    return $row ?: null;
  }

  public static function search(array $filters, bool $availableOnly = true): array {
    $pdo = Database::conn();
    $where = [];
    $params = [];

    if (!empty($filters['code'])) { $where[] = "code LIKE ?"; $params[] = "%{$filters['code']}%"; }
    if (!empty($filters['renting_location'])) { $where[] = "renting_location LIKE ?"; $params[] = "%{$filters['renting_location']}%"; }
    if (!empty($filters['description'])) { $where[] = "description LIKE ?"; $params[] = "%{$filters['description']}%"; }

    $sql = "
      SELECT *
      FROM motorbikes m
      WHERE m.is_active=1
    ";
    if ($availableOnly) {
      $sql .= "
      AND NOT EXISTS (
        SELECT 1 FROM rentals r
        WHERE r.motorbike_id = m.id AND r.status='ONGOING'
      )
      ";
    }
    if ($where) $sql .= " AND (" . implode(" AND ", $where) . ")";
    $sql .= " ORDER BY id DESC";

    $st = $pdo->prepare($sql);
    $st->execute($params);
    return $st->fetchAll();
  }

  public static function all(): array {
    return Database::conn()->query("SELECT * FROM motorbikes ORDER BY id DESC")->fetchAll();
  }

  public static function available(): array {
    return Database::conn()->query("\n      SELECT *\n      FROM motorbikes m\n      WHERE m.is_active=1\n      AND NOT EXISTS (\n        SELECT 1 FROM rentals r\n        WHERE r.motorbike_id = m.id AND r.status='ONGOING'\n      )\n      ORDER BY m.id DESC\n    ")->fetchAll();
  }
}