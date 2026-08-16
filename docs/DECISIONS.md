2026-07-29

Entry är projektets centrala objekt.

Inte Game.



## Entry och Releases

Entry är det centrala arkivobjektet.

En Entry kan ha flera Releases. Release används för att representera
en specifik release kopplad till Entry.

## Repository Pattern

Repository Pattern används för åtkomst till databasen.

Databasanrop ska ligga i repositories i stället för direkt i controllers.

## Databasschema och migrationer

MariaDB används som databas.

Migrationsfilerna är den auktoritativa källan för databasschemat.
Databasschemat ska ändras genom migrationer och inte genom
odokumenterade manuella ändringar.

## Arkivstruktur

Entry är det centrala arkivobjektet.

En Entry kan ha flera Releases och en Release kan ha flera ReleaseFiles.
Detta håller den logiska posten separerad från de fysiska arkivfilerna.

## Utskriftsvyer

Utskrifter har separata views och en separat print-layout.

Det finns en lista för utskrift och en detaljerad utskrift.
Print-vyerna ska inte använda den vanliga webbapplikationens
huvudlayout.
