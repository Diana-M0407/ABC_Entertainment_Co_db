<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../flash.php';
require_once __DIR__ . '/../util.php';
$user = current_user();
$flash = flash_get_all();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= h($title ?? 'ABC Entertainment') ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="topbar">
  <div class="container topbar__inner">
    <a class="brand" href="index.php">ABC Entertainment</a>
    <nav class="nav">
      <a href="cinemas.php">Cinemas</a>
      <a href="auditoriums.php">Auditoriums</a>
      <a href="movies.php">Movies</a>
      <a href="showtime_search.php">Showtime Search</a>
      <a href="views.php">Views</a>
      <?php if ($user): ?>
        <a href="employees.php">Employees</a>
        <span class="nav__user">Hi, <?= h($user['fname'] ?? $user['username']) ?></span>
        <a class="btn btn--ghost" href="logout.php">Logout</a>
      <?php else: ?>
        <a class="btn btn--primary" href="login.php">Login</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<main class="container">
  <?php foreach ($flash as $f): ?>
    <div class="alert alert--<?= h($f['type']) ?>"><?= h($f['message']) ?></div>
  <?php endforeach; ?>
