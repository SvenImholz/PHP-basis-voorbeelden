<?php
declare(strict_types=1);
require __DIR__ . "/db.php";

$db->exec(
    "CREATE TABLE IF NOT EXISTS feedback (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        naam TEXT NOT NULL,
        bericht TEXT NOT NULL,
        cijfer INTEGER,
        aangemaakt TEXT NOT NULL
    )"
);

echo "Tabel feedback staat klaar. Ga naar <a href=\"/\">index.php</a>";
