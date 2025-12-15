<?php
$title = "Movies";
$pdo = require __DIR__ . '/db.php';
require __DIR__ . '/partials/header.php';

$q = trim($_GET['q'] ?? '');
$genre = trim($_GET['genre'] ?? '');

$genres = $pdo
  ->query("SELECT Genre_Id, Genre_type FROM GENRES ORDER BY Genre_type")
  ->fetchAll();

$sql = "
SELECT
  m.Movie_Id,
  m.Title,
  m.Description,
  m.Year,
  m.Duration,
  m.Release_Date,
  r.Rating,
  GROUP_CONCAT(g.Genre_type ORDER BY g.Genre_type SEPARATOR ', ') AS Genres
FROM MOVIE m
LEFT JOIN RATING r ON r.Rate_Id = m.Rate_Id
LEFT JOIN MOVIE_GENRE mg ON mg.Movie_Id = m.Movie_Id
LEFT JOIN GENRES g ON g.Genre_Id = mg.Genre_Id
WHERE
  (:q1 = ''
    OR m.Title LIKE CONCAT('%', :q2, '%')
    OR m.Description LIKE CONCAT('%', :q3, '%'))
  AND (:g1 = '' OR g.Genre_type = :g2)
GROUP BY m.Movie_Id
ORDER BY m.Title
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
  ':q1' => $q,
  ':q2' => $q,
  ':q3' => $q,
  ':g1' => $genre,
  ':g2' => $genre
]);

$rows = $stmt->fetchAll();
?>

<div class="card">
  <h1 class="h1">Movies</h1>

  <form method="get" class="row" style="margin-top:8px;">
    <div class="field">
      <label>Search title / description</label>
      <input name="q" value="<?= h($q) ?>" placeholder="e.g., Interstellar">
    </div>

    <div class="field">
      <label>Genre</label>
      <select name="genre">
        <option value="">All genres</option>
        <?php foreach ($genres as $g): ?>
          <option value="<?= h($g['Genre_type']) ?>"
            <?= $genre === $g['Genre_type'] ? 'selected' : '' ?>>
            <?= h($g['Genre_type']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="field" style="flex:0 0 auto;">
      <label>&nbsp;</label>
      <button class="btn" type="submit">Apply</button>
    </div>
  </form>

  <div class="field" style="margin-top:10px;">
    <label>Quick filter (client-side)</label>
    <input data-table-filter="movieTable"
           placeholder="Type to filter table instantly...">
  </div>

  <table class="table" id="movieTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Year</th>
        <th>Duration</th>
        <th>Rating</th>
        <th>Genres</th>
        <th>Release</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= (int)$r['Movie_Id'] ?></td>
          <td>
            <div style="font-weight:800;">
              <?= h($r['Title']) ?>
            </div>
            <div class="muted" style="font-size:13px; margin-top:4px;">
              <?= h(mb_strimwidth($r['Description'] ?? '', 0, 90, '…')) ?>
            </div>
          </td>
          <td><?= (int)$r['Year'] ?></td>
          <td><?= (int)$r['Duration'] ?> min</td>
          <td><span class="badge"><?= h($r['Rating'] ?? '—') ?></span></td>
          <td class="muted"><?= h($r['Genres'] ?? '') ?></td>
          <td class="muted"><?= h($r['Release_Date']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
