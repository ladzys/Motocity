<?php
class Validator {
  public static function required(string $v): bool { return trim($v) !== ''; }
  public static function email(string $v): bool { return filter_var($v, FILTER_VALIDATE_EMAIL) !== false; }
  public static function phone(string $v): bool { return preg_match('/^[0-9+\-\s]{6,20}$/', $v) === 1; }
  public static function money($v): bool { return is_numeric($v) && floatval($v) >= 0; }
  public static function code(string $v): bool { return preg_match('/^[A-Za-z0-9\-]{3,30}$/', $v) === 1; }
  public static function positiveInt($v): bool {
    return filter_var($v, FILTER_VALIDATE_INT) !== false && (int)$v > 0;
  }

  public static function dateTime(string $v): bool {
    $d = DateTime::createFromFormat('Y-m-d\TH:i', $v);
    return $d !== false;
  }
}