<?php
// session.php - start session consistently
$config = require __DIR__ . '/config.php';
if (session_status() === PHP_SESSION_NONE) {
  session_name($config['app']['session_name']);
  session_start();
}
