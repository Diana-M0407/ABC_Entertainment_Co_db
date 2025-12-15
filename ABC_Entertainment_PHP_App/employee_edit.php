<?php
$title = "Edit Employee";
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/flash.php';
require_once __DIR__ . '/util.php';
require_login();

$pdo = require __DIR__ . '/db.php';
$cinemas = $pdo->query("SELECT Cinema_Id, Name FROM CINEMA ORDER BY Name")->fetchAll();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM EMPLOYEE WHERE Employee_Id = :id");
$stmt->execute([':id' => $id]);
$emp = $stmt->fetch();
if (!$emp) { http_response_code(404); echo "Employee not found."; exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();
  $data = [
    'id' => $id,
    'Fname' => trim($_POST['Fname'] ?? ''),
    'Lname' => trim($_POST['Lname'] ?? ''),
    'Phone_no' => trim($_POST['Phone_no'] ?? ''),
    'Street_no' => trim($_POST['Street_no'] ?? ''),
    'Street_name' => trim($_POST['Street_name'] ?? ''),
    'City' => trim($_POST['City'] ?? ''),
    'State' => trim($_POST['State'] ?? ''),
    'ZIP_code' => trim($_POST['ZIP_code'] ?? ''),
    'Email' => trim($_POST['Email'] ?? ''),
    'Cinema_Id' => ($_POST['Cinema_Id'] === '' ? null : (int)$_POST['Cinema_Id']),
  ];

  try {
    $stmt = $pdo->prepare("UPDATE EMPLOYEE
      SET Fname=:Fname, Lname=:Lname, Phone_no=:Phone_no,
          Street_no=:Street_no, Street_name=:Street_name, City=:City, State=:State, ZIP_code=:ZIP_code,
          Email=:Email, Cinema_Id=:Cinema_Id
      WHERE Employee_Id=:id");
    $stmt->execute($data);
    flash_set('ok', 'Employee updated.');
    header("Location: employees.php");
    exit;
  } catch (PDOException $e) {
    flash_set('bad', 'Update failed. (Email might already exist.)');
  }
}

require __DIR__ . '/partials/header.php';
?>
<div class="card" style="max-width:860px; margin:0 auto;">
  <h1 class="h1">Edit Employee #<?= (int)$id ?></h1>

  <form method="post">
    <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>">

    <div class="row">
      <div class="field"><label>First name</label><input name="Fname" required value="<?= h($emp['Fname']) ?>"></div>
      <div class="field"><label>Last name</label><input name="Lname" required value="<?= h($emp['Lname']) ?>"></div>
      <div class="field"><label>Email</label><input name="Email" type="email" required value="<?= h($emp['Email']) ?>"></div>
    </div>

    <div class="row">
      <div class="field"><label>Phone</label><input name="Phone_no" value="<?= h($emp['Phone_no']) ?>"></div>
      <div class="field"><label>Cinema</label>
        <select name="Cinema_Id">
          <option value="">(none)</option>
          <?php foreach ($cinemas as $c): ?>
            <option value="<?= (int)$c['Cinema_Id'] ?>" <?= ((string)$c['Cinema_Id']===(string)$emp['Cinema_Id'])?'selected':''; ?>>
              <?= h($c['Name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="h2" style="margin-top:14px;">Address</div>
    <div class="row">
      <div class="field"><label>Street No</label><input name="Street_no" value="<?= h($emp['Street_no']) ?>"></div>
      <div class="field"><label>Street Name</label><input name="Street_name" value="<?= h($emp['Street_name']) ?>"></div>
    </div>
    <div class="row">
      <div class="field"><label>City</label><input name="City" value="<?= h($emp['City']) ?>"></div>
      <div class="field"><label>State</label><input name="State" value="<?= h($emp['State']) ?>"></div>
      <div class="field"><label>ZIP</label><input name="ZIP_code" value="<?= h($emp['ZIP_code']) ?>"></div>
    </div>

    <div style="margin-top:12px; display:flex; gap:10px; flex-wrap:wrap;">
      <button class="btn btn--ok" type="submit">Save</button>
      <a class="btn btn--ghost" href="employees.php">Cancel</a>
    </div>
  </form>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
