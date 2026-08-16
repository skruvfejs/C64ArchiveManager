# Database

C64 Archive Manager använder MariaDB.

Databasschemat byggs och ändras genom migrationssystemet i:

    database/migrations/

Migrationsfilerna är den auktoritativa källan för databasschemat.

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

## Tabeller

### roles

Användarroller.

### users

Användarkonton och användarrelaterade inställningar.

### entry_types

Definierar typen av en Entry.

### entries

Det centrala arkivobjektet.

Viktiga fält:

- `id` – primärnyckel
- `entry_type_id` – koppling till `entry_types`
- `title` – titel
- `sort_title` – titel för sortering
- `year` – år
- `description` – beskrivning
- `status` – status
- `created_at` – skapad
- `updated_at` – senast ändrad

En Entry kan ha flera Releases.

### releases

Specifika releases kopplade till en Entry.

Viktiga fält:

- `id` – primärnyckel
- `entry_id` – koppling till `entries`
- `name` – release-namn
- `version` – version
- `notes` – anteckningar
- `created_at` – skapad
- `updated_at` – senast ändrad

Kombinationen `entry_id + name + version` är unik.

När en Entry tas bort tas dess Releases bort genom cascade.

En Entry kan ha flera Releases.

### release_files

Fysiska filer kopplade till en Release.

Viktiga fält:

- `id` – primärnyckel
- `release_id` – koppling till `releases`
- `filename` – filnamn
- `format` – filformat
- `disk_name` – diskens namn
- `disk_id` – disk-ID
- `path` – fysisk sökväg
- `size` – filstorlek
- `crc32` – CRC32
- `md5` – MD5
- `sha1` – SHA1
- `created_at` – skapad
- `updated_at` – senast ändrad

Kombinationen `release_id + filename` är unik.

MD5, SHA1 och CRC32 är indexerade.

En Release kan ha flera ReleaseFiles.

### bam

Information som lästs från diskens BAM.

Viktiga fält:

- `id` – primärnyckel
- `release_file_id` – koppling till `release_files`
- `disk_name` – diskens namn
- `disk_id` – disk-ID
- `dos_type` – DOS-typ
- `blocks_free` – lediga block
- `blocks_used` – använda block
- `created_at` – skapad
- `updated_at` – senast ändrad

Varje ReleaseFile kan ha högst en BAM-post.

### directory_entries

Katalogposter som lästs från diskbilder.

Viktiga fält:

- `id` – primärnyckel
- `release_file_id` – koppling till `release_files`
- `filename` – filnamn
- `directory_position` – position i katalogen
- `filetype` – filtyp
- `start_track` – startspår
- `start_sector` – startsektor
- `blocks` – antal block
- `file_offset` – offset i filen
- `file_size` – filstorlek
- `locked` – låst fil
- `closed` – stängd fil
- `created_at` – skapad
- `updated_at` – senast ändrad

Katalogposten är kopplad till den ReleaseFile från vilken den lästes.

Senare migrationer kompletterar tabellen med T64-relaterade fält.

### images

Bildreferenser kopplade till Entries.

Viktiga fält:

- `id` – primärnyckel
- `entry_id` – koppling till `entries`
- `type` – bildtyp
- `filename` – filnamn
- `path` – sökväg
- `width` – bredd
- `height` – höjd
- `created_at` – skapad
- `updated_at` – senast ändrad

`entry_id` refererar till `entries`.

### tags

Gemensamma taggar som kan kopplas till Entries, Releases och diskfiler.

Viktiga fält:

- `id` – primärnyckel
- `name` – taggnamn
- `description` – beskrivning
- `created_at` – skapad
- `updated_at` – senast ändrad

Taggnamnet är unikt.

### entry_tags

Koppling mellan Entries och Tags.

Fält:

- `entry_id` – koppling till `entries`
- `tag_id` – koppling till `tags`
- `created_at` – när kopplingen skapades

Primärnyckeln består av:

`entry_id + tag_id`

Både Entry och Tag har foreign keys med cascade-delete.

### release_tags

Koppling mellan Releases och Tags.

### disk_tags

Koppling mellan ReleaseFiles och Tags.

### import_logs

Information om genomförda importer.

### audit_logs

Logg över viktiga system- och administrationshändelser.

### settings

Systeminställningar som nyckel/värde.

## Migrationssystem

Migrationerna körs i nummerordning och används för att bygga och uppdatera databasschemat.

Databasschemat ska ändras genom migrationer och inte genom odokumenterade manuella ändringar.
