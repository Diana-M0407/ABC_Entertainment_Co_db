<?php
// util.php
function h(?string $s): string { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }

function password_policy_ok(string $pw): array {
  // >=8 chars, >=1 upper, >=1 lower, >=1 digit, >=1 special
  $ok = true;
  $errors = [];
  if (strlen($pw) < 8) { $ok = false; $errors[] = "At least 8 characters."; }
  if (!preg_match('/[A-Z]/', $pw)) { $ok = false; $errors[] = "At least 1 uppercase letter."; }
  if (!preg_match('/[a-z]/', $pw)) { $ok = false; $errors[] = "At least 1 lowercase letter."; }
  if (!preg_match('/[0-9]/', $pw)) { $ok = false; $errors[] = "At least 1 number."; }
  if (!preg_match('/[^A-Za-z0-9]/', $pw)) { $ok = false; $errors[] = "At least 1 special character."; }
  return [$ok, $errors];
}

function csrf_token(): string {
  require_once __DIR__ . '/session.php';
  if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
  return $_SESSION['csrf'];
}
function csrf_check(): void {
  require_once __DIR__ . '/session.php';
  $t = $_POST['csrf'] ?? '';
  if (empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $t)) {
    http_response_code(400);
    echo "Bad request (CSRF).";
    exit;
  }
}
