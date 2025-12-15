<?php
// flash.php
require_once __DIR__ . '/session.php';

function flash_set(string $type, string $message): void {
  $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function flash_get_all(): array {
  $msgs = $_SESSION['flash'] ?? [];
  unset($_SESSION['flash']);
  return $msgs;
}
