<?php
$title = "Cinemas";
$pdo = require __DIR__ . '/db.php';
require __DIR__ . '/partials/header.php';

$rows = $pdo->query("SELECT Cinema_Id, Name, Street_no, Street_name, City, State, ZIP_code, Creation_Time FROM CINEMA ORDER BY Cinema_Id")->fetchAll();
?>
<div class="card">
  <h1 class="h1">Cinemas</h1>
  <div class="row" style="margin-top:8px;">
    <div class="field" style="flex:1">
      <label>Filter (client-side)</label>
      <input data-table-filter="cinemaTable" placeholder="Type to filter...">
    </div>
  </div>

  <table class="table" id="cinemaTable">
    <thead>
      <tr>
        <th>ID</th><th>Name</th><th>Address</th><th>Created</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= (int)$r['Cinema_Id'] ?></td>
          <td><?= h($r['Name']) ?></td>
          <td><?= h($r['Street_no'].' '.$r['Street_name'].', '.$r['City'].', '.$r['State'].' '.$r['ZIP_code']) ?></td>
          <td class="muted"><?= h($r['Creation_Time']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
