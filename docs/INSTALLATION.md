# Installation

## Systemkrav

C64 Archive Manager är utvecklad och testad med:

- PHP 8.3
- Composer 2.x
- MariaDB 10.11
- PHP PDO med `pdo_mysql`
- Git

Följande PHP-extensioner används eller finns i den testade miljön:

- curl
- fileinfo
- intl
- mbstring
- mysqli
- mysqlnd
- openssl
- PDO
- pdo_mysql
- zip

## Testad miljö

Utvecklingsmiljön som används för projektet:

- Debian-baserad Linux
- PHP 8.3.6
- Composer 2.7.1
- MariaDB 10.11.14

## Hämta projektet

Klona repositoryt:

    git clone git@github.com:skruvfejs/C64ArchiveManager.git
    cd C64ArchiveManager

Installera Composer-beroenden:

    composer install

## MariaDB

Skapa en databas och en användare för C64 Archive Manager.

Exempel:

    CREATE DATABASE c64archive CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    CREATE USER 'c64archive'@'localhost' IDENTIFIED BY 'BYT_TILL_EGET_LÖSENORD';
    GRANT ALL PRIVILEGES ON c64archive.* TO 'c64archive'@'localhost';
    FLUSH PRIVILEGES;

## Databaskonfiguration

Applikationen behöver databasuppgifter för MariaDB.

Använd projektets befintliga konfigurationsmekanism och ange:

- databasnamn
- databasvärd
- databasport
- databasanvändare
- databaslösenord

Exakta konfigurationsnycklar ska följa projektets aktuella konfigurationsfil.

## Migrationer

När databasen är skapad körs projektets migrationer från projektroten.

Kontrollera först status:

    php migrate.php status

Kör sedan migrationerna:

    php migrate.php migrate

Kontrollera därefter status igen:

    php migrate.php status

## Storage och importfiler

Projektet använder `storage/` för importerade filer och annan
filbaserad lagring.

Vid uppladdning via `/import` skickas diskimagen från klientens
dator till servern och sparas under:

    storage/imports

Webbserverns PHP-process måste kunna läsa och skriva till de kataloger
som används av applikationen.

## Importerade filformat

C64 Archive Manager stöder följande filformat vid import:

- D64 – Commodore 1541-diskimage
- D71 – Commodore 1571-diskimage
- D81 – Commodore 1581-diskimage
- T64 – Tape/filarkiv
- PRG – C64-programfil
- P00 – PC64-programfil

PRG-filer importeras som PRG-releaser.

P00-filer läses och PRG-innehållet extraheras vid import. Den
resulterande filen registreras som en PRG-release.

Importformatet identifieras automatiskt av applikationen.

## Fil- och katalogrättigheter

Projektet behöver kunna läsa PHP-filer och skriva till `storage/`.

Exempel på rättigheter i utvecklingsmiljön:

    chmod 775 storage
    chmod 775 storage/attachments
    chmod 775 storage/backups
    chmod 775 storage/cache
    chmod 775 storage/imports
    chmod 775 storage/logs
    chmod 775 storage/thumbnails

PHP-filer kan ha:

    chmod 664 sökväg/till/fil.php

Projektets filer och kataloger ska ägas av den användare/grupp som kör
utvecklingsmiljön. Använd inte `chmod 777` på hela projektet.

Kontrollera rättigheterna med:

    ls -ld storage storage/imports
    find storage -maxdepth 2 -type d -exec ls -ld {} \;

## Starta lokalt

För lokal utveckling kan PHP:s inbyggda webbserver användas:

    php -S localhost:8000 -t public

Öppna sedan:

    http://localhost:8000

## Första start och test

Öppna applikationen i webbläsaren och kontrollera att startsidan visas.

Testa därefter:

- logga in med ett administratörskonto
- öppna Arkiv
- öppna Diskar
- kontrollera att administrationen är åtkomlig
- kontrollera att en importerad disk kan öppnas
- kontrollera att diskbilden kan laddas ner
- kontrollera att utskriftsvyerna fungerar

## Felsökning

### Databasanslutningen fungerar inte

Kontrollera att MariaDB körs och att databasuppgifterna är korrekta.

Kontrollera även att PHP har `pdo_mysql` installerat:

    php -m | grep pdo_mysql

### Migrationer fungerar inte

Kontrollera migrationsstatus:

    php migrate.php status

Kontrollera även PHP-syntax:

    php -l migrate.php

### Import fungerar inte

Kontrollera att `storage/imports` finns och att PHP-processen har
nödvändiga rättigheter till katalogen.

### Sidan ger HTTP 500

Kontrollera PHP:s felmeddelande och serverlogg. Kör även syntaxkontroll
på den berörda PHP-filen:

    php -l sökväg/till/fil.php
