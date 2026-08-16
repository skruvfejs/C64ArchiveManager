The most important file.

This document explains why the system has been built the way it has.

Exempel:

Entry is the main object.
Release represents a specific release.
ReleaseFile represents a physical file.
DirectoryEntry representerar en katalogpost.
The Repository Pattern is used.


## Project Structure

- `app/Core/` – ramverkets centrala funktioner
- `app/Entity/` – entities
- `app/Models/` – modeller
- `app/Http/Controllers/` – controllers
- `app/Http/Middleware/` – middleware and authorization checks
- `app/Repositories/` – database access
- `app/Services/` – imports, disk handling and other business logic
- `app/Views/` – web interface and print views
- `routes/` – webbapplikationens routes

## Request Flow

A typical request mainly passes through:

`Router → Middleware → Controller → Service/Repository → View`

Controllers handle the request and prepare data for the view.
Repositories are responsible for database access and services handle more
omfattande logik.

## Repository Pattern

Repositories in `app/Repositories/` are responsible for database access.

Examples include `EntryRepository`, `ReleaseRepository`,
`ReleaseFileRepository` and `DirectoryEntryRepository`.

Controllers should normally not contain SQL directly.

## Services

Services in `app/Services/` contain more extensive logic that does not belong
hemma direkt i controllers eller repositories.

Examples include D64/D71/D81 import, disk parsing, directory reading,
checksum/MD5, backup, storage and database import/export.

## Centrala arkivobjekt

`Entry` is the central archive object.

An `Entry` can have multiple `Release` records.

A `Release` represents a specific release.

A `ReleaseFile` represents the physical file belonging to a
release.

`DirectoryEntry` represents a directory entry from the disk contents.

The relationship can be simplified as:

`Entry → Release → ReleaseFile → DirectoryEntry`

## Views

Views in `app/Views/` are responsible for presentation.

The standard layout is `layouts/main.php`.

The print views use a separate layout:

- `entry/print-list.php`
- `entry/print-details.php`
- `layouts/print.php`
- `layouts/print.css`

The print views do not use the standard main layout.

## Authorization and Middleware

Authentication and authorization are handled centrally.

Middleware in `app/Http/Middleware/` is used for, among other things:

- inloggning
- redigering
- import
- user administration
- tagghantering
- systemadministration
- loggar
- maintenance mode

Permissions and roles are handled by the Core layer's auth and
authorization-funktioner.

## Import System

Import functionality is primarily located in `app/Services/` and is handled
via `ImportController`.

The system supports import of D64, D71 and D81 as well as PRG-related
files and T64.

Importers and parsers are separated so that format-specific logic
is kept separate.

MD5 is used for duplicate detection and directory contents can be read from
importerade diskimages.

## Pagination and Downloads

List views use pagination to handle larger numbers of records.

The disk image itself can be downloaded from the archive through the separate
filhanteringen.

Pagination does not affect the print views. These retrieve the entire current
postens relevanta information.

## Language and Database

The system supports Swedish and English through the language files
`lang/sv.php` och `lang/en.php`.

MariaDB is used as the database. Database access is handled through the Core layer's
database functions and repositories.

Database structure and relationships are documented separately in
`docs/DATABASE.md`.
