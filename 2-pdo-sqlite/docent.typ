// Docentblad — niet uitdelen
// Sven Imholz

#set document(title: "PDO + SQLite — docent", author: "Sven Imholz")
#set page(margin: 2cm)
#set text(size: 11pt)

= Les PDO + SQLite — docent

Niet uitdelen. Slides: `slides/pdo-sqlite.typ` (Touying). \
Starters: `starters/` (`db.php`, `install.php`, `index.php`).

== Kader
- 2 uur, eerstejaars, zo eenvoudig mogelijk.
- PHP + SQLite + PDO. Geen framework, geen Postgres.
- Doel: formulier → tabel, `INSERT`, `SELECT`.

== Klaarzetten
- PHP met PDO SQLite.
- `php -S localhost:8000 -t starters`
- Bij het eerste keer gebruik van de app navigeer je naar: `localhost:8000/install.php
      - Optioneel: DB Browser for SQLite voor `app.db`.

      == Tijd
      Zie slide Lesplan. Live demo 5–10 min bij INSERT/SELECT. \
      Papieren oefening “3 velden” max 5 min; daarna steiger feedback ok.

      == Check einde les
      Student legt uit: veld → kolom; `prepare`/`execute`; `fetchAll` → HTML. \
      Vaak: `install.php` vergeten, HTML `name` ≠ SQL-kolom.

      == Compileren
      ```
typst compile slides/pdo-sqlite.typ slides/pdo-sqlite.pdf
```
