<?php
$title = "SQL Views";
$pdo = require __DIR__ . '/db.php';
require __DIR__ . '/partials/header.php';

$v1 = $pdo->query("SELECT * FROM Long_Movies_By_Genre")->fetchAll();
$v2 = $pdo->query("SELECT * FROM Cinema_3D_Screens")->fetchAll();
$v3 = $pdo->query("SELECT * FROM Movies_Shown_More_Than_3_Times")->fetchAll();

// Group v1 by Genre for display (matches project wording)
$grouped = [];
foreach ($v1 as $r) {
  $grouped[$r['Genre']][] = $r;
}
?>
<div class="card">
  <h1 class="h1">Required SQL Views</h1>
  <p class="p">
    These pages read the views created in your SQL script and present them nicely.
    View #1 is grouped by genre at the UI level.
  </p>
</div>

<div class="card">
  <div class="h2">V1 — Long Movies by Genre (Duration &gt; 120)</div>
  <?php foreach ($grouped as $genre => $rows): ?>
    <div style="margin-top:12px;">
      <div class="badge badge--ok"><?= h($genre) ?></div>
      <table class="table" style="margin-top:10px;">
        <thead><tr><th>Cinema</th><th>Auditorium</th><th>Movie</th><th>Duration</th></tr></thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= h($r['Cinema_Name']) ?></td>
            <td><?= h($r['Auditorium_Name']) ?></td>
            <td><?= h($r['Movie_Title']) ?></td>
            <td><?= (int)$r['Duration'] ?> min</td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endforeach; ?>
</div>

<div class="card">
  <div class="h2">V2 — 3D Screenings (Cinema + Address + Auditorium + Capacity)</div>
  <table class="table">
    <thead><tr><th>Cinema</th><th>Address</th><th>Auditorium</th><th>Capacity</th><th>3D</th></tr></thead>
    <tbody>
    <?php foreach ($v2 as $r): ?>
      <tr>
        <td><?= h($r['Cinema_Name']) ?></td>
        <td class="muted"><?= h($r['Address']) ?></td>
        <td><?= h($r['Auditorium_Name']) ?></td>
        <td><?= (int)$r['Capacity'] ?></td>
        <td><span class="badge badge--ok">Yes</span></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<div class="card">
  <div class="h2">V3 — Movies shown more than 3 times per day</div>
  <table class="table">
    <thead><tr><th>Date</th><th>Movie</th><th>Show Count</th></tr></thead>
    <tbody>
    <?php foreach ($v3 as $r): ?>
      <tr>
        <td><?= h($r['Show_Date']) ?></td>
        <td><?= h($r['Movie_Title']) ?></td>
        <td><span class="badge badge--warn"><?= (int)$r['Show_Count'] ?></span></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
