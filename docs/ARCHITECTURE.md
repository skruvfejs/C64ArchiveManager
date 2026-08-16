Den viktigaste filen.

Här dokumenterar vi varför vi byggt systemet som vi gjort.

Exempel:

Entry är huvudobjektet.
Release är en specifik utgåva.
ReleaseFile är en fysisk fil.
DirectoryEntry representerar en katalogpost.
Repository Pattern används.


## Projektstruktur

- `app/Core/` – ramverkets centrala funktioner
- `app/Entity/` – entities
- `app/Models/` – modeller
- `app/Http/Controllers/` – controllers
- `app/Http/Middleware/` – middleware och behörighetskontroller
- `app/Repositories/` – databasanrop
- `app/Services/` – import, diskhantering och övrig affärslogik
- `app/Views/` – webbgränssnitt och utskriftsvyer
- `routes/` – webbapplikationens routes

## Request-flöde

En vanlig request går i huvudsak genom:

`Router → Middleware → Controller → Service/Repository → View`

Controllers hanterar requesten och förbereder data för vyn.
Repositories ansvarar för databasanrop och services hanterar mer
omfattande logik.

## Repository Pattern

Repositories i `app/Repositories/` ansvarar för databasanrop.

Exempel är `EntryRepository`, `ReleaseRepository`,
`ReleaseFileRepository` och `DirectoryEntryRepository`.

Controllers ska normalt inte innehålla SQL direkt.

## Services

Services i `app/Services/` innehåller mer omfattande logik som inte hör
hemma direkt i controllers eller repositories.

Exempel är import av D64/D71/D81, diskparsning, katalogläsning,
checksum/MD5, backup, storage och databasimport/export.

## Centrala arkivobjekt

`Entry` är det centrala arkivobjektet.

En `Entry` kan ha flera `Release`.

En `Release` representerar en specifik utgåva.

En `ReleaseFile` representerar den fysiska filen som hör till en
release.

`DirectoryEntry` representerar en katalogpost från diskens innehåll.

Relationen kan förenklat beskrivas som:

`Entry → Release → ReleaseFile → DirectoryEntry`

## Views

Views i `app/Views/` ansvarar för presentationen.

Den vanliga layouten är `layouts/main.php`.

Utskriftsvyerna använder en separat layout:

- `entry/print-list.php`
- `entry/print-details.php`
- `layouts/print.php`
- `layouts/print.css`

Print-vyerna använder inte den vanliga huvudlayouten.

## Behörigheter och middleware

Autentisering och behörighetskontroll hanteras centralt.

Middleware i `app/Http/Middleware/` används bland annat för:

- inloggning
- redigering
- import
- användaradministration
- tagghantering
- systemadministration
- loggar
- underhållsläge

Behörigheter och roller hanteras av Core-lagrets auth- och
authorization-funktioner.

## Importsystemet

Importfunktionerna finns huvudsakligen i `app/Services/` och hanteras
via `ImportController`.

Systemet har stöd för import av D64, D71 och D81 samt PRG-relaterade
filer och T64.

Importerare och parsers är separerade så att format-specifik logik
hålls åtskild.

MD5 används för dubblettkontroll och kataloginnehåll kan läsas från
importerade diskimages.

## Pagination och nedladdning

Listvyer använder pagination för att hantera större mängder poster.

Själva diskbilden kan laddas ner från arkivet via den separata
filhanteringen.

Pagination påverkar inte utskriftsvyerna. Dessa hämtar hela den aktuella
postens relevanta information.

## Språk och databas

Systemet har stöd för svenska och engelska via språkfilerna
`lang/sv.php` och `lang/en.php`.

MariaDB används som databas. Databasåtkomst sker via Core-lagrets
databasefunktioner och repositories.

Databasstruktur och relationer dokumenteras separat i
`docs/DATABASE.md`.
