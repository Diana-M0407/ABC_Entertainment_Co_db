<?php
$title = "Login";
require_once __DIR__ . '/flash.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/util.php';

if (is_logged_in()) {
  header("Location: index.php");
  exit;
}

$pdo = require __DIR__ . '/db.php';

$identifier = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();

  $identifier = trim($_POST['identifier'] ?? '');
  $password   = $_POST['password'] ?? '';

  $sql = "
    SELECT
      u.Username AS username,
      u.Password_Hash AS password_hash,
      e.Employee_Id,
      e.Fname AS fname,
      e.Lname AS lname,
      e.Email,
      e.Cinema_Id
    FROM USER_ACCOUNT u
    JOIN EMPLOYEE e ON e.Employee_Id = u.Employee_Id
    WHERE u.Username = :id1
       OR e.Email    = :id2
    LIMIT 1
  ";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':id1' => $identifier,
    ':id2' => $identifier
  ]);

  $row = $stmt->fetch();

  if ($row && password_verify($password, $row['password_hash'])) {
    $_SESSION['user'] = [
      'username'    => $row['username'],
      'employee_id' => $row['Employee_Id'],
      'fname'       => $row['fname'],
      'lname'       => $row['lname'],
      'email'       => $row['Email'],
      'cinema_id'   => $row['Cinema_Id'],
    ];

    flash_set('ok', 'Welcome back, ' . $row['fname'] . '!');
    header("Location: index.php");
    exit;
  } else {
    flash_set('bad', 'Invalid login. Try username or employee email + correct password.');
  }
}

require __DIR__ . '/partials/header.php';
?>

<div class="card" style="max-width:540px; margin: 0 auto;">
  <h1 class="h1">Employee Login</h1>
  <p class="p">Use your <b>username</b> or your employee <b>email</b>.</p>

  <form method="post" class="grid" style="margin-top:10px;">
    <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>">

    <div class="field">
      <label>Username or Email</label>
      <input
        name="identifier"
        value="<?= h($identifier) ?>"
        required
        placeholder="e.g., aly.paul@company.com">
    </div>

    <div class="field">
      <label>Password</label>
      <input
        name="password"
        type="password"
        required
        placeholder="••••••••">
    </div>

    <button class="btn btn--primary" type="submit">Login</button>
  </form>

  <div style="margin-top:12px; display:flex; gap:10px; flex-wrap:wrap;">
    <a class="btn btn--ghost" href="create_user.php">Create account</a>
    <a class="btn btn--ghost" href="index.php">Back</a>
  </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
