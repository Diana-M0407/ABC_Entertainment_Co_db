<?php
$title = "Roles";
$pdo = require __DIR__ . '/db.php';
require __DIR__ . '/partials/header.php';
$rows = $pdo->query("SELECT Role_Id, Role_Name, Creation_Time FROM ROLES ORDER BY Role_Id")->fetchAll();
?>
<div class="card">
  <h1 class="h1">Roles</h1>
  <table class="table">
    <thead><tr><th>ID</th><th>Role</th><th>Created</th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= (int)$r['Role_Id'] ?></td>
          <td><?= h($r['Role_Name']) ?></td>
          <td class="muted"><?= h($r['Creation_Time']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
