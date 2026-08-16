# Installation

## System Requirements

C64 Archive Manager is developed and tested with:

- PHP 8.3
- Composer 2.x
- MariaDB 10.11
- PHP PDO med `pdo_mysql`
- Git

The following PHP extensions are used or available in the tested environment:

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

## Tested Environment

The development environment used for the project:

- Debian-baserad Linux
- PHP 8.3.6
- Composer 2.7.1
- MariaDB 10.11.14

## Get the Project

Clone the repository:

    git clone git@github.com:skruvfejs/C64ArchiveManager.git
    cd C64ArchiveManager

Install Composer dependencies:

    composer install

## MariaDB

Create a database and a user for C64 Archive Manager.

Example:

    CREATE DATABASE c64archive CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    CREATE USER 'c64archive'@'localhost' IDENTIFIED BY 'CHANGE_TO_YOUR_OWN_PASSWORD';
    GRANT ALL PRIVILEGES ON c64archive.* TO 'c64archive'@'localhost';
    FLUSH PRIVILEGES;

## Database Configuration

The application requires MariaDB database credentials.

Use the project's existing configuration mechanism and specify:

- database name
- database host
- database port
- database user
- database password

The exact configuration keys must follow the project's current configuration file.

## Migrations

Once the database has been created, run the project's migrations from the project root.

Check the status first:

    php migrate.php status

Then run the migrations:

    php migrate.php migrate

Check the status again:

    php migrate.php status

## Storage and Import Files

The project uses `storage/` for imported files and other
file-based storage.

When uploading through `/import`, the disk image is sent from the client's
dator till servern och sparas under:

    storage/imports

The web server's PHP process must be able to read from and write to the
directories used by the application.

## Supported Import Formats

C64 Archive Manager supports the following file formats for import:

- D64 – Commodore 1541-diskimage
- D71 – Commodore 1571-diskimage
- D81 – Commodore 1581-diskimage
- T64 – Tape/filarkiv
- PRG – C64-programfil
- P00 – PC64-programfil

PRG files are imported as PRG releases.

P00 files are read and the PRG content is extracted during import. The
resulting file is registered as a PRG release.

The import format is detected automatically by the application.

## File and Directory Permissions

The project needs to be able to read PHP files and write to `storage/`.

Example permissions for the development environment:

    chmod 775 storage
    chmod 775 storage/attachments
    chmod 775 storage/backups
    chmod 775 storage/cache
    chmod 775 storage/imports
    chmod 775 storage/logs
    chmod 775 storage/thumbnails

PHP-filer kan ha:

    chmod 664 path/to/file.php

The project's files and directories should be owned by the user/group running the
development environment. Do not use `chmod 777` on the entire project.

Check the permissions with:

    ls -ld storage storage/imports
    find storage -maxdepth 2 -type d -exec ls -ld {} \;

## Run Locally

For local development, PHP's built-in web server can be used:

    php -S localhost:8000 -t public

Then open:

    http://localhost:8000

## First Start and Testing

Open the application in a web browser and verify that the start page is displayed.

Then test:

- log in with an administrator account
- open Archive
- open Disks
- verify that administration is accessible
- verify that an imported disk can be opened
- verify that the disk image can be downloaded
- verify that the print views work

## Troubleshooting

### Database Connection Does Not Work

Verify that MariaDB is running and that the database credentials are correct.

Also verify that PHP has `pdo_mysql` installed:

    php -m | grep pdo_mysql

### Migrations Do Not Work

Check migration status:

    php migrate.php status

Also check PHP syntax:

    php -l migrate.php

### Import Does Not Work

Verify that `storage/imports` exists and that the PHP process has the
necessary permissions for the directory.

### The Page Returns HTTP 500

Check PHP's error message and the server log. Also run a syntax check
on the affected PHP file:

    php -l path/to/file.php
