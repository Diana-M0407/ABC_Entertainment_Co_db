<?php
$title = "Employees";
require_once __DIR__ . '/auth.php';
require_login();

$pdo = require __DIR__ . '/db.php';
require __DIR__ . '/partials/header.php';

$sql = "SELECT e.Employee_Id, e.Fname, e.Lname, e.Email, e.Phone_no,
               c.Name AS Cinema_Name,
               GROUP_CONCAT(r.Role_Name ORDER BY r.Role_Name SEPARATOR ', ') AS Roles,
               e.Creation_Time
        FROM EMPLOYEE e
        LEFT JOIN CINEMA c ON c.Cinema_Id = e.Cinema_Id
        LEFT JOIN PERFORMS p ON p.Employee_Id = e.Employee_Id
        LEFT JOIN ROLES r ON r.Role_Id = p.Role_Id
        GROUP BY e.Employee_Id
        ORDER BY e.Employee_Id";
$rows = $pdo->query($sql)->fetchAll();
?>
<div class="card">
  <h1 class="h1">Employees</h1>
  <p class="p">Employee profiles include name, address, phone, email (business rule #5).</p>

  <div style="margin-top:12px; display:flex; gap:10px; flex-wrap:wrap;">
    <a class="btn btn--ok" href="employee_add.php">Add employee</a>
  </div>

  <div class="field" style="margin-top:10px;">
    <label>Quick filter (client-side)</label>
    <input data-table-filter="empTable" placeholder="Type to filter employees...">
  </div>

  <table class="table" id="empTable">
    <thead>
      <tr>
        <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Cinema</th><th>Roles</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= (int)$r['Employee_Id'] ?></td>
          <td><?= h($r['Fname'].' '.$r['Lname']) ?></td>
          <td class="muted"><?= h($r['Email']) ?></td>
          <td class="muted"><?= h($r['Phone_no']) ?></td>
          <td><?= h($r['Cinema_Name'] ?? '—') ?></td>
          <td class="muted"><?= h($r['Roles'] ?? '') ?></td>
          <td style="white-space:nowrap;">
            <a class="btn btn--ghost" href="employee_edit.php?id=<?= (int)$r['Employee_Id'] ?>">Edit</a>
            <a class="btn btn--danger" href="employee_delete.php?id=<?= (int)$r['Employee_Id'] ?>">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
