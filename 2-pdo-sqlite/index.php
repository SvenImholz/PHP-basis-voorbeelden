<?php
declare(strict_types=1);
// Bij een error 'no such table: feedback' moet je naar /install.php navigeren in je browser
require __DIR__ . "/db.php";

$fout = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $naam = trim($_POST["naam"] ?? "");
    $bericht = trim($_POST["bericht"] ?? "");
    $cijfer_raw = trim($_POST["cijfer"] ?? "");
    $cijfer = $cijfer_raw === "" ? null : (int) $cijferRaw;

    if ($naam === "" || $bericht === "") {
        $fout = "Naam en bericht zijn verplicht.";
    } else {
        $insert_statement = $db->prepare(
            "INSERT INTO feedback (naam, bericht, cijfer)
             VALUES (?, ?, ?)"
        );
        $insert_statement->execute([$naam, $bericht, $cijfer]);

        header("Location: /2-pdo-sqlite");
        exit();
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
    <style>
        :root {
            --bg: #edd8d8;
            --text: #232020;
        }

        /* Dark mode automatically enabled */
        @media (prefers-color-scheme: dark) {
            :root {
                --bg: #151313;
                --text: #e6e3e3;
            }
        }

        body {
            /*Light and dark mode settings*/
            background: var(--bg);
            color: var(--text);
            transition: background-color 0.3s ease;

            /*Basic side by side layout*/
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        section {
            outline: var(--text) solid 1px;
            padding: 1rem;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
<section>
    <h1>Feedback</h1>

    <?php if ($fout !== ""): ?>
        <p><strong><?= htmlspecialchars($fout, ENT_QUOTES, "UTF-8") ?></strong></p>
    <?php endif; ?>

    <form method="post">
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
</section>
<section>
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
</section>
</body>
</html>
