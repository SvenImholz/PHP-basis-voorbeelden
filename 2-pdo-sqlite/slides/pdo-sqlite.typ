// Red de gegevens — PDO + SQLite
// Auteur: Sven Imholz
// Les: ~2 uur, eerstejaars Software Developer
// Compileren: typst compile slides/pdo-sqlite.typ slides/pdo-sqlite.pdf

#import "@preview/touying:0.6.1": *
#import themes.university: *

#show: university-theme.with(
  aspect-ratio: "16-9",
  config-info(
    title: [Formulier → database],
    subtitle: [PHP · SQLite · PDO],
    author: [Sven Imholz],
    date: datetime.today(),
    institution: [Software Developer — jaar 1],
  ),
)

#title-slide()

== Doel van deze les

Na 2 uur kun je
- Uit een HTML-formulier een tabelschema afleiden
- Met PDO een SQLite-bestand openen
- Een rij *opslaan* (`INSERT`)
- Rijen *tonen* (`SELECT`)

Eén `.sqlite`-bestand.

== Lesplan (2 uur)

#table(
  columns: (auto, 1fr),
  stroke: 0.5pt,
  [*Tijd*], [*Onderdeel*],
  [0:00–0:15], [Waarom een database?],
  [0:15–0:35], [Formulier → kolommen],
  [0:35–0:55], [SQLite + PDO openen + `CREATE TABLE`],
  [0:55–1:25], [Opslaan (`INSERT`)],
  [1:25–1:50], [Tonen (`SELECT`)],
  [1:50–2:00], [Check + wat je inlevert],
)

= Waarom een database?

== Formulier zonder database

```
Gebruiker vult in → PHP echo't bedankt → data weg
```

Morgen wil je de inzendingen *nog* zien.
Dan moet je ze *ergens* bewaren.

== SQLite in één zin

Een database = *één bestand* op schijf.
Bijvoorbeeld `app.db`.

- Geen aparte server nodig voor deze les
- Past bij kleine oefeningen en lokale ontwikkelomgeving

= Formulier → kolommen

== Nieuw formulier: `feedback`

Velden op de pagina:

- *naam* (verplicht)
- *bericht* (verplicht)
- *cijfer* 1–10 (optioneel)

Vraag: welke kolommen heeft de tabel nodig?

== Antwoord: `feedback` tabel

#table(
  columns: (auto, auto, 1fr),
  stroke: 0.5pt,
  [*Kolom*], [*Type*], [*Waarom*],
  [`id`], [`INTEGER` PK], [unieke sleutel, auto],
  [`naam`], [`TEXT`], [uit het formulier],
  [`bericht`], [`TEXT`], [uit het formulier],
  [`cijfer`], [`INTEGER`], [mag leeg → `NULL`],
  [`aangemaakt`], [`TEXT`], [tijdstip ISO-8601 \ `DEFAULT (datetime('now'))`],
)
ISO-8601 string [YYYY-MM-DD HH:MM:SS.SSS]

Regel: *elk invoerveld dat je bewaart → kolom.*
Plus vaak: `id` en een tijdstip.

== Oefening (5 min)

Op papier: kies *jouw* mini-formulier (3 velden).
Schrijf kolomnaam + type erbij.
Later bouw je dít uit — of je gebruikt feedback.

= PDO + SQLite

== Verbinding openen

```php
<?php
$db = new PDO("sqlite:" . __DIR__ . "/app.db");
$db->setAttribute(
  PDO::ATTR_ERRMODE,
  PDO::ERRMODE_EXCEPTION
);
```

- `"sqlite:…"` = driver + pad naar bestand
- Bestand bestaat nog niet? SQLite maakt het aan bij schrijven
- Exceptions aan = fouten zie je meteen

== Bestand `db.php` (steiger)

Eén plek voor de verbinding.
Andere pagina’s doen: `require "db.php";`

Zie map `starters/`.

= CREATE TABLE

== Schema uit de kolommen

```sql
CREATE TABLE IF NOT EXISTS feedback (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  naam TEXT NOT NULL,
  bericht TEXT NOT NULL,
  cijfer INTEGER,
  aangemaakt TEXT NOT NULL
);
```

`IF NOT EXISTS` → script mag vaker draaien.

== Wanneer draai je dit?

Optie A (deze les): eenmalig in `install.php`
Optie B: bij de eerste request in `db.php`

Wij gebruiken *install.php* — bewust, zichtbaar.

= Opslaan

== Van POST naar INSERT

1. Formulier: `method="post"`
2. PHP leest `$_POST["naam"]` enz.
3. Controleren: niet leeg
4. `prepare` + `execute` → `INSERT`

Nooit zo: `"… '" . $_POST["naam"] . "'"`
Wél: placeholders `?` of `:naam`.

== Code (kern)

```php
$sql = "INSERT INTO feedback
  (naam, bericht, cijfer, aangemaakt)
  VALUES (?, ?, ?, ?)";
$st = $db->prepare($sql);
$st->execute([
  $naam,
  $bericht,
  $cijfer,           // of null
  date("c"),
]);
```

Daarna: doorsturen naar de lijstpagina.

= Tonen

== SELECT → HTML

```php
$rijen = $db->query(
  "SELECT id, naam, bericht, cijfer, aangemaakt
   FROM feedback
   ORDER BY id DESC"
)->fetchAll(PDO::FETCH_ASSOC);
```

Daarna een `foreach` en veilig tonen met `htmlspecialchars`.

== Drie bestanden

#table(
  columns: (auto, 1fr),
  stroke: 0.5pt,
  [*Bestand*], [*Rol*],
  [`db.php`], [PDO-verbinding],
  [`install.php`], [`CREATE TABLE`],
  [`index.php`], [formulier + opslaan + lijst],
)

(Of formulier en lijst splitsen — mag.)

= Aan de slag

== Stappen in de les

1. Kopieer `starters/`
2. Draai `install.php` één keer in de browser
3. Open `index.php` — stuur 2 berichten in
4. Vernieuw: zie je ze in de lijst?
5. Open `app.db` in DB Browser *of* laat `var_dump` weg en vertrouw de HTML

== Klaar als

- Tabel bestaat (na `install.php`)
- Formulier schrijft een rij
- Pagina toont alle rijen
- Je kunt in eigen woorden: formulier → kolom → `INSERT` / `SELECT`

== Veelgemaakte fouten

- Vergeten `install.php` → “no such table”
- `name="…"` in HTML ≠ kolomnaam in SQL
- Geen `htmlspecialchars` → rare HTML bij `<` in bericht
- Pad naar `app.db` klopt niet (andere map)

== Volgende stap (niet vandaag)

- Aparte `bedankt.php`
- `UPDATE` / verwijderen
- Validatie cijfer 1–10 hard afdwingen
- Zelfde patroon met PostgreSQL later

#focus-slide[
  Formulier → kolommen → `INSERT` / `SELECT`
]
