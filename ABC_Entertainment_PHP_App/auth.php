<?php
// auth.php
require_once __DIR__ . '/session.php';

function is_logged_in(): bool {
  return !empty($_SESSION['user']);
}

function require_login(): void {
  if (!is_logged_in()) {
    header("Location: login.php");
    exit;
  }
}

function current_user(): ?array {
  return $_SESSION['user'] ?? null;
}

function logout(): void {
  $_SESSION = [];
  if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
      $params["path"], $params["domain"],
      $params["secure"], $params["httponly"]
    );
  }
  session_destroy();
}
