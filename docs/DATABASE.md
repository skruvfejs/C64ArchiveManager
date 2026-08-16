# Database

C64 Archive Manager uses MariaDB.

The database schema is built and modified through the migration system in:

    database/migrations/

The migration files are the authoritative source for the database schema.

## Centrala relationer

    Entry
      |
      +-- Release
            |
            +-- ReleaseFile
                  |
                  +-- BAM
                  |
                  +-- DirectoryEntry

    Entry       -- EntryTag   -- Tag
    Release     -- ReleaseTag -- Tag
    ReleaseFile -- DiskTag    -- Tag

## Tables

### roles

User roles.

### users

User accounts and user-related settings.

### entry_types

Definierar typen av en Entry.

### entries

Det centrala arkivobjektet.

Important fields:

- `id` – primary key
- `entry_type_id` – koppling till `entry_types`
- `title` – titel
- `sort_title` – title used for sorting
- `year` – year
- `description` – beskrivning
- `status` – status
- `created_at` – skapad
- `updated_at` – last modified

An Entry can have multiple Releases.

### releases

Specifika releases kopplade till en Entry.

Important fields:

- `id` – primary key
- `entry_id` – koppling till `entries`
- `name` – release-namn
- `version` – version
- `notes` – anteckningar
- `created_at` – skapad
- `updated_at` – last modified

The combination `entry_id + name + version` is unique.

When an Entry is deleted, its Releases are deleted through cascade.

An Entry can have multiple Releases.

### release_files

Fysiska filer kopplade till en Release.

Important fields:

- `id` – primary key
- `release_id` – koppling till `releases`
- `filename` – filnamn
- `format` – filformat
- `disk_name` – diskens namn
- `disk_id` – disk-ID
- `path` – physical path
- `size` – filstorlek
- `crc32` – CRC32
- `md5` – MD5
- `sha1` – SHA1
- `created_at` – skapad
- `updated_at` – last modified

The combination `release_id + filename` is unique.

MD5, SHA1 and CRC32 are indexed.

A Release can have multiple ReleaseFiles.

### bam

Information read from the disk's BAM.

Important fields:

- `id` – primary key
- `release_file_id` – koppling till `release_files`
- `disk_name` – diskens namn
- `disk_id` – disk-ID
- `dos_type` – DOS-typ
- `blocks_free` – lediga block
- `blocks_used` – used blocks
- `created_at` – skapad
- `updated_at` – last modified

Each ReleaseFile can have at most one BAM record.

### directory_entries

Directory entries read from disk images.

Important fields:

- `id` – primary key
- `release_file_id` – koppling till `release_files`
- `filename` – filnamn
- `directory_position` – position i katalogen
- `filetype` – filtyp
- `start_track` – startspyear
- `start_sector` – startsektor
- `blocks` – antal block
- `file_offset` – offset i filen
- `file_size` – filstorlek
- `locked` – locked file
- `closed` – closed file
- `created_at` – skapad
- `updated_at` – last modified

The directory entry is linked to the ReleaseFile from which it was read.

Later migrations extend the table with T64-related fields.

### images

Bildreferenser kopplade till Entries.

Important fields:

- `id` – primary key
- `entry_id` – koppling till `entries`
- `type` – bildtyp
- `filename` – filnamn
- `path` – path
- `width` – bredd
- `height` – height
- `created_at` – skapad
- `updated_at` – last modified

`entry_id` refererar till `entries`.

### tags

Gemensamma taggar som kan kopplas till Entries, Releases och diskfiler.

Important fields:

- `id` – primary key
- `name` – taggnamn
- `description` – beskrivning
- `created_at` – skapad
- `updated_at` – last modified

The tag name is unique.

### entry_tags

Koppling mellan Entries och Tags.

Fields:

- `entry_id` – koppling till `entries`
- `tag_id` – koppling till `tags`
- `created_at` – when the relationship was created

The primary key consists of:

`entry_id + tag_id`

Both Entry and Tag have foreign keys with cascade-delete.

### release_tags

Koppling mellan Releases och Tags.

### disk_tags

Koppling mellan ReleaseFiles och Tags.

### import_logs

Information about completed imports.

### audit_logs

Log of important system and administrative events.

### settings

System settings stored as key/value pairs.

## Migrationssystem

Migrations are run in numerical order and are used to build and update the database schema.

The database schema must be changed through migrations and not through undocumented manual changes.
