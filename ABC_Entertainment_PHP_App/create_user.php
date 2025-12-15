<?php
$title = "Create User";
require_once __DIR__ . '/flash.php';
require_once __DIR__ . '/util.php';
require_once __DIR__ . '/auth.php';

$pdo = require __DIR__ . '/db.php';

$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check();

  $username = trim($_POST['username'] ?? '');
  $email    = trim($_POST['email'] ?? '');
  $pw       = $_POST['password'] ?? '';
  $pw2      = $_POST['password2'] ?? '';

  if ($pw !== $pw2) {
    flash_set('bad', 'Passwords do not match.');
  } else {
    [$ok, $errs] = password_policy_ok($pw);
    if (!$ok) {
      flash_set('bad', 'Password policy: ' . implode(' ', $errs));
    } else {
      // Find employee by email
      $stmt = $pdo->prepare("
        SELECT Employee_Id
        FROM EMPLOYEE
        WHERE Email = :email
        LIMIT 1
      ");
      $stmt->execute([':email' => $email]);
      $emp = $stmt->fetch();

      if (!$emp) {
        flash_set('bad', 'No employee found with that email.');
      } else {
        $hash = password_hash($pw, PASSWORD_DEFAULT);
        try {
          $stmt = $pdo->prepare("
            INSERT INTO USER_ACCOUNT (Username, Employee_Id, Password_Hash)
            VALUES (:u, :eid, :h)
          ");
          $stmt->execute([
            ':u'   => $username,
            ':eid' => $emp['Employee_Id'],
            ':h'   => $hash
          ]);

          flash_set('ok', 'Account created. You can login now.');
          header("Location: login.php");
          exit;
        } catch (PDOException $e) {
          flash_set(
            'bad',
            'Create failed (username already exists or this employee already has an account).'
          );
        }
      }
    }
  }
}

require __DIR__ . '/partials/header.php';
?>

<div class="card" style="max-width:640px; margin: 0 auto;">
  <h1 class="h1">Create Employee Account</h1>
  <p class="p">
    Use your <b>employee email</b> to create a login account.
    Passwords are securely stored using hashing.
  </p>

  <form method="post" class="grid" style="margin-top:10px;">
    <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>">

    <div class="row">
      <div class="field">
        <label>Employee Email</label>
        <input
          name="email"
          type="email"
          value="<?= h($email) ?>"
          required
          placeholder="e.g., aly.paul@company.com">
      </div>

      <div class="field">
        <label>Username</label>
        <input
          name="username"
          value="<?= h($username) ?>"
          required
          placeholder="e.g., alypaul">
      </div>
    </div>

    <div class="row">
      <div class="field">
        <label>Password</label>
        <input
          type="password"
          name="password"
          required
          placeholder="Min 8, upper/lower/number/special">
      </div>

      <div class="field">
        <label>Confirm Password</label>
        <input
          type="password"
          name="password2"
          required
          placeholder="Repeat password">
      </div>
    </div>

    <button class="btn btn--ok" type="submit">Create account</button>
  </form>

  <div style="margin-top:12px; display:flex; gap:10px; flex-wrap:wrap;">
    <a class="btn btn--ghost" href="login.php">Back to login</a>
    <a class="btn btn--ghost" href="index.php">Home</a>
  </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
