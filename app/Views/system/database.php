<?php

declare(strict_types=1);
?>

<h1>Databas</h1>

<p>
    Här administrerar du databasen för C64 Archive Manager.
</p>

<hr>

<h2>Status</h2>

<table style="width:100%;">

    <tr style="vertical-align:top;">

        <td style="width:50%;">

            <table>

                <tr>
                    <td><strong>Databas</strong></td>
                    <td><?= htmlspecialchars((string) $database) ?></td>
                </tr>

                <tr>
                    <td><strong>MariaDB-version</strong></td>
                    <td><?= htmlspecialchars((string) $version) ?></td>
                </tr>

                <tr>
                    <td><strong>Antal tabeller</strong></td>
                    <td><?= (int) $tables ?></td>
                </tr>

                <tr>
                    <td><strong>Databasstorlek</strong></td>
                    <td><?= htmlspecialchars((string) $size) ?></td>
                </tr>

            </table>

        </td>

        <td style="width:50%;">

            <table>

                <tr>
                    <td><strong>Senaste säkerhetskopia</strong></td>
                    <td>
                        <?php if (!empty($lastBackup)): ?>
                            <?= htmlspecialchars($lastBackup['filename']) ?>
                        <?php else: ?>
                            Ingen säkerhetskopia funnen
                        <?php endif; ?>
                    </td>
                </tr>

                <tr>
                    <td><strong>Antal säkerhetskopior</strong></td>
                    <td><?= (int) $backupCount ?></td>
                </tr>

                <tr>
                    <td><strong>Backuputrymme</strong></td>
                    <td><?= htmlspecialchars((string) $backupSize) ?></td>
                </tr>

            </table>

        </td>

    </tr>

</table>

<hr>

<h2>Snabbåtgärder</h2>

<form method="post" action="/administration/system/backup/create">
    <button type="submit">
        Skapa säkerhetskopia nu
    </button>
</form>

<hr>

<h2>Export</h2>

<form method="post" action="/administration/system/export">

    <p>

        <label>
            <input
                type="radio"
                name="type"
                value="full"
                checked
            >
            Hela databasen
        </label>

        <br>

        <label>
            <input
                type="radio"
                name="type"
                value="archive"
            >
            Arkivdata
        </label>

        <br>

        <label>
            <input
                type="radio"
                name="type"
                value="system"
            >
            Systemdata
        </label>

    </p>

    <p>

        <label>
            Beskrivning
        </label>

        <br>

        <input
            type="text"
            name="description"
            size="60"
        >

    </p>

    <button type="submit">
        Exportera
    </button>

</form>

<hr>

<h2>Import</h2>

<form
    method="post"
    action="/administration/system/import"
    enctype="multipart/form-data"
>

    <p>

        <label>
            SQL-fil
        </label>

        <br>

        <input
            type="file"
            name="backup"
            required
        >

    </p>

    <button type="submit">
        Importera
    </button>

</form>

<hr>

<h2>Säkerhetskopior</h2>

<?php if (empty($backups)): ?>

<p>
    Inga säkerhetskopior finns.
</p>

<?php else: ?>

<table>

    <tr>
        <th style="text-align:left;">Fil</th>
        <th style="text-align:left;">Typ</th>
        <th style="text-align:left;">Storlek</th>
        <th style="text-align:left;">Datum</th>
        <th style="text-align:left;">Åtgärder</th>
    </tr>

    <?php foreach ($backups as $backup): ?>

    <tr>

        <td>
            <?= htmlspecialchars($backup['filename']) ?>
        </td>

        <td>
            <?= htmlspecialchars($backup['type']) ?>
        </td>

        <td>
            <?= htmlspecialchars($backup['sizeText']) ?>
        </td>

        <td>
            <?= date(
                'Y-m-d H:i:s',
                $backup['modified']
            ) ?>
        </td>

        <td>

            <a href="/administration/system/backup/download?file=<?= urlencode($backup['filename']) ?>">
                Ladda ner
            </a>

            |

            <form
                method="post"
                action="/administration/system/backup/restore"
                style="display:inline;"
                onsubmit="return confirm('Är du säker på att du vill återställa denna säkerhetskopia? En automatisk säkerhetskopia skapas innan återställningen.');"
            >

                <input
                    type="hidden"
                    name="file"
                    value="<?= htmlspecialchars($backup['filename']) ?>"
                >

                <button
                    type="submit"
                    style="
                        background:none;
                        border:none;
                        padding:0;
                        margin:0;
                        color:#06c;
                        text-decoration:underline;
                        cursor:pointer;
                        font:inherit;
                    "
                >
                    Återställ
                </button>

            </form>

            |
            <form
                method="post"
                action="/administration/system/backup/delete"
                style="display:inline;"
                onsubmit="return confirm('Ta bort säkerhetskopian?');"
            >

                <input
                    type="hidden"
                    name="file"
                    value="<?= htmlspecialchars($backup['filename']) ?>"
                >

                <button
                    type="submit"
                    style="
                        background:none;
                        border:none;
                        padding:0;
                        margin:0;
                        color:#06c;
                        text-decoration:underline;
                        cursor:pointer;
                        font:inherit;
                    "
                >
                    Ta bort
                </button>

            </form>

        </td>

    </tr>

    <?php endforeach; ?>

</table>

<?php endif; ?>
