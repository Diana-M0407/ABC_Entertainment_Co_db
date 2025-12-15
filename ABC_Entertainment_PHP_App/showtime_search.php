<?php
$title = "Showtime Search";
$pdo = require __DIR__ . '/db.php';
require __DIR__ . '/partials/header.php';

$cinema = trim($_GET['cinema'] ?? '');
$date   = trim($_GET['date'] ?? '');
$time   = trim($_GET['time'] ?? '');

// Dropdown data
$cinemas = $pdo->query("
  SELECT Cinema_Id, Name
  FROM CINEMA
  ORDER BY Name
")->fetchAll();

$sql = "
SELECT
  c.Name AS Cinema,
  a.Name AS Auditorium,
  m.Title AS Movie,
  s.Start_time,
  s.End_time,
  s.Format
FROM SHOWS s
JOIN AUDITORIUM a ON a.Auditorium_Id = s.Auditorium_Id
JOIN CINEMA c     ON c.Cinema_Id = a.Cinema_Id
JOIN MOVIE m      ON m.Movie_Id = s.Movie_Id
WHERE
  (:c1 = '' OR c.Cinema_Id = :c2)
  AND (:d1 = '' OR DATE(s.Start_time) = :d2)
  AND (:t1 = '' OR TIME(s.Start_time) >= :t2)
ORDER BY s.Start_time
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
  ':c1' => $cinema,
  ':c2' => $cinema,
  ':d1' => $date,
  ':d2' => $date,
  ':t1' => $time,
  ':t2' => $time,
]);

$rows = $stmt->fetchAll();
?>

<div class="card">
  <h1 class="h1">Search Showtimes</h1>

  <form method="get" class="row" style="margin-top:8px;">
    <div class="field">
      <label>Cinema</label>
      <select name="cinema">
        <option value="">All cinemas</option>
        <?php foreach ($cinemas as $c): ?>
          <option value="<?= (int)$c['Cinema_Id'] ?>"
            <?= ((string)$cinema === (string)$c['Cinema_Id']) ? 'selected' : '' ?>>
            <?= h($c['Name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="field">
      <label>Date</label>
      <input type="date" name="date" value="<?= h($date) ?>">
    </div>

    <div class="field">
      <label>Start time (optional)</label>
      <input type="time" name="time" value="<?= h($time) ?>">
    </div>

    <div class="field" style="flex:0 0 auto;">
      <label>&nbsp;</label>
      <button class="btn btn--ghost" type="submit">Search</button>
    </div>
  </form>

  <table class="table" style="margin-top:14px;">
    <thead>
      <tr>
        <th>Cinema</th>
        <th>Auditorium</th>
        <th>Movie</th>
        <th>Start</th>
        <th>End</th>
        <th>Format</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= h($r['Cinema']) ?></td>
          <td><?= h($r['Auditorium']) ?></td>
          <td><?= h($r['Movie']) ?></td>
          <td><?= h($r['Start_time']) ?></td>
          <td><?= h($r['End_time']) ?></td>
          <td><span class="badge"><?= h($r['Format']) ?></span></td>
        </tr>
      <?php endforeach; ?>

      <?php if (!$rows): ?>
        <tr>
          <td colspan="6" class="muted">No showtimes found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
