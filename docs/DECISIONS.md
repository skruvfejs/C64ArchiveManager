2026-07-29

Entry is the central object of the project.

Inte Game.



## Entry och Releases

Entry is the central archive object.

An Entry can have multiple Releases. Release is used to represent
a specific release associated with an Entry.

## Repository Pattern

The Repository Pattern is used for database access.

Database access should be placed in repositories instead of directly in controllers.

## Databasschema och migrationer

MariaDB is used as the database.

The migration files are the authoritative source for the database schema.
The database schema must be changed through migrations and not through
undocumented manual changes.

## Arkivstruktur

Entry is the central archive object.

En Entry kan ha flera Releases och en Release kan ha flera ReleaseFiles.
This keeps the logical record separate from the physical archive files.

## Utskriftsvyer

Utskrifter har separata views och en separat print-layout.

There is a list print view and a detailed print view.
The print views should not use the standard web application's
huvudlayout.
