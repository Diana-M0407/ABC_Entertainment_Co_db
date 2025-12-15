<?php
$title = "Delete Employee";
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/flash.php';
require_once __DIR__ . '/util.php';
require_login();

$pdo = require __DIR__ . '/db.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT Employee_Id, CONCAT(Fname,' ',Lname) AS Name, Email FROM EMPLOYEE WHERE Employee_Id=:id");
$stmt->execute([':id' => $id]);
$emp = $stmt->fetch();
if (!$emp) { http_response_code(404); echo "Employee not found."; exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  try {
    // USER_ACCOUNT row will cascade delete per schema if employee gets deleted.
    $stmt = $pdo->prepare("DELETE FROM EMPLOYEE WHERE Employee_Id = :id");
    $stmt->execute([':id' => $id]);
    flash_set('ok', 'Employee deleted.');
    header("Location: employees.php");
    exit;
  } catch (PDOException $e) {
    flash_set('bad', 'Delete failed (check foreign key constraints).');
  }
}

require __DIR__ . '/partials/header.php';
?>
<div class="card" style="max-width:720px; margin:0 auto;">
  <h1 class="h1">Delete Employee</h1>
  <p class="p">Are you sure you want to delete <b><?= h($emp['Name']) ?></b> (<?= h($emp['Email']) ?>)?</p>

  <form method="post" style="margin-top:12px; display:flex; gap:10px; flex-wrap:wrap;">
    <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>">
    <button class="btn btn--danger" type="submit">Yes, delete</button>
    <a class="btn btn--ghost" href="employees.php">Cancel</a>
  </form>

  <div class="alert alert--warn" style="margin-top:12px;">
    Note: If this employee has a USER_ACCOUNT, it will be deleted too (CASCADE).
  </div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
