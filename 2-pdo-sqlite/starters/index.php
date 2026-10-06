<?php
declare(strict_types=1);
require __DIR__ . "/db.php";

$fout = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $naam = trim($_POST["naam"] ?? "");
    $bericht = trim($_POST["bericht"] ?? "");
    $cijferRaw = trim($_POST["cijfer"] ?? "");
    $cijfer = $cijferRaw === "" ? null : (int) $cijferRaw;

    if ($naam === "" || $bericht === "") {
        $fout = "Naam en bericht zijn verplicht.";
    } else {
        $st = $db->prepare(
            "INSERT INTO feedback (naam, bericht, cijfer, aangemaakt)
             VALUES (?, ?, ?, ?)"
        );
        $st->execute([$naam, $bericht, $cijfer, date("c")]);
        header("Location: index.php");
        exit;
    }
}

$rijen = $db->query(
    "SELECT id, naam, bericht, cijfer, aangemaakt
     FROM feedback
     ORDER BY id DESC"
)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="utf-8">
  <title>Feedback</title>
</head>
<body>
  <h1>Feedback</h1>

  <?php if ($fout !== ""): ?>
    <p><strong><?= htmlspecialchars($fout, ENT_QUOTES, "UTF-8") ?></strong></p>
  <?php endif; ?>

  <form method="post" action="index.php">
    <p>
      <label>Naam
        <input name="naam" required>
      </label>
    </p>
    <p>
      <label>Bericht
        <textarea name="bericht" required></textarea>
      </label>
    </p>
    <p>
      <label>Cijfer (1–10, optioneel)
        <input name="cijfer" type="number" min="1" max="10">
      </label>
    </p>
    <button type="submit">Opslaan</button>
  </form>

  <h2>Opgeslagen</h2>
  <?php if (count($rijen) === 0): ?>
    <p>Nog geen rijen. Draai eerst <code>install.php</code> als de tabel ontbreekt.</p>
  <?php else: ?>
    <ul>
      <?php foreach ($rijen as $rij): ?>
        <li>
          <strong><?= htmlspecialchars($rij["naam"], ENT_QUOTES, "UTF-8") ?></strong>
          <?php if ($rij["cijfer"] !== null): ?>
            (<?= (int) $rij["cijfer"] ?>)
          <?php endif; ?>
          — <?= htmlspecialchars($rij["bericht"], ENT_QUOTES, "UTF-8") ?>
          <br><small><?= htmlspecialchars($rij["aangemaakt"], ENT_QUOTES, "UTF-8") ?></small>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</body>
</html>
