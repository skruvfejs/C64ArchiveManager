# C64 Archive Manager

C64 Archive Manager is a web-based archive system for cataloguing and managing a Commodore 64 archive.

## Purpose

The system is used to register and search for C64 programs and releases, and to store information about the disk images belonging to the archive.

An **Entry** represents the central archive object.

An Entry can have multiple **Releases**, and a Release can have one or more **ReleaseFiles**.

## Main Features

- Entry management
- Release management
- Disk file management
- Import of D64, D71, D81, T64, PRG and P00
- Web-based import
- MD5-based duplicate detection
- Directory content parsing
- Search and sorting
- Pagination
- Disk image downloads
- List and detail print views
- Tags
- Users, roles and permissions
- System administration
- Database import and export
- Swedish and English language files

## Technology

The project is built in PHP and uses a custom MVC-based framework.

The database is MariaDB.

The project uses the Repository Pattern for database access.

## Documentation

| Document | Contents |
|---|---|
| [INSTALLATION.md](docs/INSTALLATION.md) | Installation guide |
| [ARCHITECTURE.md](docs/ARCHITECTURE.md) | System architecture and core objects |
| [DATABASE.md](docs/DATABASE.md) | Database structure and relationships |
| [DECISIONS.md](docs/DECISIONS.md) | Important architectural decisions |
| [CHANGELOG.md](docs/CHANGELOG.md) | Changes between versions |
| [ROADMAP.md](docs/ROADMAP.md) | Remaining work and future development |

## Project Status

The project has reached version 1.0 functionality according to the current development plan.

Remaining work mainly consists of documentation, maintenance and possible future development.
