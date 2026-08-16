# C64 Archive Manager

C64 Archive Manager är ett webbaserat arkivsystem för att katalogisera och hantera ett Commodore 64-arkiv.

## Syfte

Systemet används för att registrera och söka efter C64-program och releaser samt lagra information om de diskbilder som hör till arkivet.

En **Entry** representerar det centrala arkivobjektet.

En Entry kan ha flera **Releases**, och en Release kan ha en eller flera **ReleaseFiles**.

## Huvudfunktioner

- Hantering av Entries
- Hantering av Releases
- Hantering av diskfiler
- Import av D64, D71 och D81
- Webbaserad import
- MD5-baserad dubblettkontroll
- Läsning av kataloginnehåll
- Sökning och sortering
- Pagination
- Nedladdning av diskbilder
- Utskriftsvyer för lista och detaljer
- Taggar
- Användare, roller och behörigheter
- Systemadministration
- Databasimport och export
- Svenska och engelska språkfiler

## Teknik

Projektet är byggt i PHP och använder ett eget MVC-baserat ramverk.

Databasen är MariaDB.

Projektet använder Repository Pattern för åtkomst till databasen.

## Dokumentation

| Dokument | Innehåll |
|---|---|
| [INSTALLATION.md](docs/INSTALLATION.md) | Installationsguide |
| [ARCHITECTURE.md](docs/ARCHITECTURE.md) | Systemets arkitektur och centrala objekt |
| [DATABASE.md](docs/DATABASE.md) | Databasstruktur och relationer |
| [DECISIONS.md](docs/DECISIONS.md) | Viktiga arkitekturbeslut |
| [CHANGELOG.md](docs/CHANGELOG.md) | Förändringar mellan versioner |
| [ROADMAP.md](docs/ROADMAP.md) | Återstående arbete och framtida utveckling |

## Projektstatus

Projektet har nått version 1.0-funktionalitet enligt den nuvarande utvecklingsplanen.

Återstående arbete består huvudsakligen av dokumentation, underhåll och eventuell framtida vidareutveckling.
