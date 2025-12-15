<?php
$title = "Auditoriums";
$pdo = require __DIR__ . '/db.php';
require __DIR__ . '/partials/header.php';

$only3d = isset($_GET['only3d']) ? 1 : 0;
$onlyimax = isset($_GET['onlyimax']) ? 1 : 0;

$sql = "SELECT a.Auditorium_Id, a.Name AS Auditorium_Name, a.Capacity, a.Support_3D, a.Support_IMAX,
               c.Name AS Cinema_Name, a.Creation_Time
        FROM AUDITORIUM a
        JOIN CINEMA c ON c.Cinema_Id = a.Cinema_Id
        WHERE (:only3d = 0 OR a.Support_3D = 1)
          AND (:onlyimax = 0 OR a.Support_IMAX = 1)
        ORDER BY c.Name, a.Name";
$stmt = $pdo->prepare($sql);
$stmt->execute([':only3d' => $only3d, ':onlyimax' => $onlyimax]);
$rows = $stmt->fetchAll();
?>
<div class="card">
  <h1 class="h1">Auditoriums</h1>
  <div class="row" style="margin-top:8px;">
    <form method="get" class="row" style="margin:0; padding:0;">
      <div class="field" style="flex:0 0 auto;">
        <label>Filters</label>
        <div class="row" style="align-items:center;">
          <label class="badge"><input type="checkbox" name="only3d" value="1" <?= $only3d?'checked':''; ?> style="margin-right:8px;">3D only</label>
          <label class="badge"><input type="checkbox" name="onlyimax" value="1" <?= $onlyimax?'checked':''; ?> style="margin-right:8px;">IMAX only</label>
          <button class="btn btn--ghost" type="submit">Apply</button>
        </div>
      </div>
      <div class="field" style="flex:1">
        <label>Quick filter (client-side)</label>
        <input data-table-filter="audTable" placeholder="Type to filter...">
      </div>
    </form>
  </div>

  <table class="table" id="audTable">
    <thead>
      <tr>
        <th>ID</th><th>Cinema</th><th>Name</th><th>Capacity</th><th>3D</th><th>IMAX</th><th>Created</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= (int)$r['Auditorium_Id'] ?></td>
          <td><?= h($r['Cinema_Name']) ?></td>
          <td><?= h($r['Auditorium_Name']) ?></td>
          <td><?= (int)$r['Capacity'] ?></td>
          <td><?= $r['Support_3D'] ? '<span class="badge badge--ok">Yes</span>' : '<span class="badge">No</span>' ?></td>
          <td><?= $r['Support_IMAX'] ? '<span class="badge badge--warn">Yes</span>' : '<span class="badge">No</span>' ?></td>
          <td class="muted"><?= h($r['Creation_Time']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
