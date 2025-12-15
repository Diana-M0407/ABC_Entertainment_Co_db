<?php
$title = "Home";
require_once __DIR__ . '/auth.php';
$pdo = require __DIR__ . '/db.php';

$counts = [
  'cinemas' => (int)$pdo->query("SELECT COUNT(*) c FROM CINEMA")->fetch()['c'],
  'auditoriums' => (int)$pdo->query("SELECT COUNT(*) c FROM AUDITORIUM")->fetch()['c'],
  'movies' => (int)$pdo->query("SELECT COUNT(*) c FROM MOVIE")->fetch()['c'],
  'shows' => (int)$pdo->query("SELECT COUNT(*) c FROM SHOWS")->fetch()['c'],
];

require __DIR__ . '/partials/header.php';
?>
<div class="card">
  <h1 class="h1">ABC Entertainment — Cinema Management System</h1>
  <div class="h2">Homepage + Login + Interactive Search (CPSC 332)</div>
  <p class="p">
    Use the navigation to browse cinemas, auditoriums, movies, and showtimes.
    Employee-only features (employee list + showtime search) require login.
  </p>

  <div class="kpis" style="margin-top:14px;">
    <div class="kpi"><div class="kpi__n"><?= (int)$counts['cinemas'] ?></div><div class="kpi__l">Cinemas</div></div>
    <div class="kpi"><div class="kpi__n"><?= (int)$counts['auditoriums'] ?></div><div class="kpi__l">Auditoriums</div></div>
    <div class="kpi"><div class="kpi__n"><?= (int)$counts['movies'] ?></div><div class="kpi__l">Movies</div></div>
    <div class="kpi"><div class="kpi__n"><?= (int)$counts['shows'] ?></div><div class="kpi__l">Showtimes</div></div>
  </div>

  <div style="margin-top:16px; display:flex; gap:10px; flex-wrap:wrap;">
    <a class="btn btn--primary" href="showtime_search.php">Search showtimes</a>
    <a class="btn btn--ghost" href="views.php">Required SQL Views</a>
    <?php if (!is_logged_in()): ?>
      <a class="btn btn--ghost" href="login.php">Employee login</a>
    <?php else: ?>
      <a class="btn btn--ghost" href="employees.php">Employees</a>
    <?php endif; ?>
  </div>
</div>

<div class="grid grid--2" style="margin-top:14px;">
  <div class="card">
    <div class="h2">Quick lookup</div>
    <p class="p">Jump into a list page and use the search box to filter instantly.</p>
    <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:10px;">
      <a class="btn btn--ghost" href="cinemas.php">Cinemas</a>
      <a class="btn btn--ghost" href="auditoriums.php">Auditoriums</a>
      <a class="btn btn--ghost" href="movies.php">Movies</a>
    </div>
  </div>

  <div class="card">
    <div class="h2">Employee access</div>
    <p class="p">Login is required by business rule. Passwords are stored securely using password_hash().</p>
    <?php if (!is_logged_in()): ?>
      <a class="btn btn--primary" href="login.php">Login</a>
      <a class="btn btn--ghost" href="create_user.php">Create user account</a>
    <?php else: ?>
      <div class="badge badge--ok">Logged in as <?= h(current_user()['username']) ?></div>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
