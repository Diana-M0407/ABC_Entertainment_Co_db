<?php
$title = "Seats";
$pdo = require __DIR__ . '/db.php';
require __DIR__ . '/partials/header.php';

$auditoriums = $pdo->query("SELECT Auditorium_Id, Name FROM AUDITORIUM ORDER BY Name")->fetchAll();
$aud = (int)($_GET['auditorium_id'] ?? 0);

$rows = [];
if ($aud) {
  $stmt = $pdo->prepare("SELECT Row_no, Seat_no FROM SEAT WHERE Auditorium_Id = :a ORDER BY Row_no, Seat_no");
  $stmt->execute([':a' => $aud]);
  $rows = $stmt->fetchAll();
}
$grid = [];
foreach ($rows as $r) { $grid[$r['Row_no']][] = $r['Seat_no']; }
?>
<div class="card">
  <h1 class="h1">Seats</h1>
  <form method="get" class="row" style="margin-top:10px;">
    <div class="field">
      <label>Auditorium</label>
      <select name="auditorium_id" required>
        <option value="" disabled <?= $aud? '' : 'selected'; ?>>Choose an auditorium...</option>
        <?php foreach ($auditoriums as $a): ?>
          <option value="<?= (int)$a['Auditorium_Id'] ?>" <?= ((int)$a['Auditorium_Id']===$aud)?'selected':''; ?>>
            <?= h($a['Name']) ?> (#<?= (int)$a['Auditorium_Id'] ?>)
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field" style="flex:0 0 auto;">
      <label>&nbsp;</label>
      <button class="btn btn--ghost" type="submit">View</button>
    </div>
  </form>
</div>

<?php if ($aud): ?>
  <div class="card">
    <div class="h2">Seat map (simple)</div>
    <?php if (!$grid): ?>
      <div class="alert alert--warn">No seats found for this auditorium.</div>
    <?php else: ?>
      <?php foreach ($grid as $rowLabel => $seats): ?>
        <div style="display:flex; gap:8px; align-items:center; margin: 10px 0;">
          <div class="badge"><?= h($rowLabel) ?></div>
          <?php foreach ($seats as $s): ?>
            <div class="badge" style="min-width:44px; justify-content:center;"><?= h($s) ?></div>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
