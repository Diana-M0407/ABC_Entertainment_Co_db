<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/flash.php';
logout();
flash_set('ok', 'Logged out.');
header("Location: index.php");
exit;
