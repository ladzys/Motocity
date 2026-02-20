<?php
require_once __DIR__ . '/Database.php';

class Rental {

  public static function isBikeAvailable(int $bikeId): bool {
    $pdo = Database::conn();
    $st = $pdo->prepare("SELECT COUNT(*) c FROM rentals WHERE motorbike_id=? AND status='ONGOING'");
    $st->execute([$bikeId]);
    return ((int)$st->fetch()['c']) === 0;
  }

  public static function rent(int $userId, int $bikeId, ?string $startTime = null): array {
    $pdo = Database::conn();
    $pdo->beginTransaction();
    try {
      if (!self::isBikeAvailable($bikeId)) {
        throw new Exception("Motorbike is already rented.");
      }

      $bikeSt = $pdo->prepare("SELECT cost_per_hour FROM motorbikes WHERE id=? AND is_active=1");
      $bikeSt->execute([$bikeId]);
      $bike = $bikeSt->fetch();
      if (!$bike) throw new Exception("Motorbike not found.");

      $start = $startTime ?: date('Y-m-d H:i:s');
      $cph = $bike['cost_per_hour'];

      $st = $pdo->prepare("
        INSERT INTO rentals (user_id,motorbike_id,start_time,cost_per_hour,status)
        VALUES (?,?,?,?, 'ONGOING')
      ");
      $st->execute([$userId,$bikeId,$start,$cph]);

      $pdo->commit();
      return ['start_time'=>$start, 'cost_per_hour'=>$cph];
    } catch (Throwable $e) {
      $pdo->rollBack();
      throw $e;
    }
  }

  public static function returnBike(int $rentalId, int $actorUserId, bool $isAdmin): array {
    $pdo = Database::conn();

    $st = $pdo->prepare("SELECT * FROM rentals WHERE id=? AND status='ONGOING'");
    $st->execute([$rentalId]);
    $r = $st->fetch();
    if (!$r) throw new Exception("Ongoing rental not found.");
    if (!$isAdmin && (int)$r['user_id'] !== $actorUserId) {
      throw new Exception("You are not allowed to return this rental.");
    }

    $end = date('Y-m-d H:i:s');

    $startTs = strtotime($r['start_time']);
    $endTs   = strtotime($end);
    $seconds = max(0, $endTs - $startTs);

    // Charge proportionally based on actual elapsed time
    $hours = $seconds / 3600;
    $total = round($hours * (float)$r['cost_per_hour'], 2);

    $up = $pdo->prepare("
      UPDATE rentals
      SET end_time=?, total_cost=?, status='COMPLETED'
      WHERE id=?
    ");
    $up->execute([$end, $total, $rentalId]);

    return ['end_time'=>$end, 'hours'=>round($hours, 2), 'total_cost'=>$total];
  }

  public static function findOngoingById(int $rentalId): ?array {
    $pdo = Database::conn();
    $st = $pdo->prepare("\n      SELECT r.*, u.first_name, u.last_name, m.code\n      FROM rentals r\n      JOIN users u ON u.id = r.user_id\n      JOIN motorbikes m ON m.id = r.motorbike_id\n      WHERE r.id=? AND r.status='ONGOING'\n    ");
    $st->execute([$rentalId]);
    $row = $st->fetch();
    return $row ?: null;
  }

  public static function currentByUser(int $userId): array {
    $pdo = Database::conn();
    $st = $pdo->prepare("
      SELECT r.*, m.code, m.renting_location, m.description
      FROM rentals r
      JOIN motorbikes m ON m.id = r.motorbike_id
      WHERE r.user_id=? AND r.status='ONGOING'
      ORDER BY r.start_time DESC
    ");
    $st->execute([$userId]);
    return $st->fetchAll();
  }

  public static function historyByUser(int $userId): array {
    $pdo = Database::conn();
    $st = $pdo->prepare("
      SELECT r.*, m.code, m.renting_location, m.description
      FROM rentals r
      JOIN motorbikes m ON m.id = r.motorbike_id
      WHERE r.user_id=? AND r.status='COMPLETED'
      ORDER BY r.end_time DESC
    ");
    $st->execute([$userId]);
    return $st->fetchAll();
  }

  public static function allCurrent(): array {
    return Database::conn()->query("
      SELECT r.*, u.email, u.first_name, u.last_name, m.code
      FROM rentals r
      JOIN users u ON u.id=r.user_id
      JOIN motorbikes m ON m.id=r.motorbike_id
      WHERE r.status='ONGOING'
      ORDER BY r.start_time DESC
    ")->fetchAll();
  }

  public static function usersCurrentlyRenting(): array {
    return Database::conn()->query("
      SELECT DISTINCT u.id, u.first_name,u.last_name,u.email,u.phone,u.user_type
      FROM users u
      JOIN rentals r ON r.user_id=u.id
      WHERE r.status='ONGOING'
      ORDER BY u.id DESC
    ")->fetchAll();
  }
}