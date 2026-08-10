<?php

declare(strict_types=1);
?>

<style>
    table {
        text-align: left;
    }

    th,
    td {
        text-align: left;
    }
</style>


<h1>Underhåll</h1>


<h2>Importlagring</h2>

<table>
    <tr>
        <th>Status</th>
        <td>
            <?php if ($storage['exists']): ?>
                <?php if (
                    $storage['readable'] &&
                    $storage['writable']
                ): ?>
                    OK
                <?php else: ?>
                    VARNING
                <?php endif; ?>
            <?php else: ?>
                FEL
            <?php endif; ?>
        </td>
    </tr>

    <tr>
        <th>Importkatalog</th>
        <td>
            <?= htmlspecialchars(
                $storage['directory']
            ) ?>
        </td>
    </tr>

    <tr>
        <th>Antal fysiska filer</th>
        <td>
            <?= htmlspecialchars(
                (string) $storage['fileCount']
            ) ?>
        </td>
    </tr>

    <tr>
        <th>Använt utrymme</th>
        <td>
            <?= htmlspecialchars(
                $storage['usedSpace']
            ) ?>
        </td>
    </tr>

    <tr>
        <th>Totalt utrymme</th>
        <td>
            <?= htmlspecialchars(
                $storage['totalSpace']
            ) ?>
        </td>
    </tr>

    <tr>
        <th>Ledigt utrymme</th>
        <td>
            <?= htmlspecialchars(
                $storage['freeSpace']
            ) ?>
        </td>
    </tr>

    <tr>
        <th>Läsbar</th>
        <td>
            <?= $storage['readable']
                ? 'Ja'
                : 'Nej'
            ?>
        </td>
    </tr>

    <tr>
        <th>Skrivbar</th>
        <td>
            <?= $storage['writable']
                ? 'Ja'
                : 'Nej'
            ?>
        </td>
    </tr>
</table>


<h2>Databas</h2>

<table>
    <tr>
        <th>Status</th>
        <td>OK</td>
    </tr>

    <tr>
        <th>Registrerade filer</th>
        <td>
            <?= htmlspecialchars(
                (string) $database['fileCount']
            ) ?>
        </td>
    </tr>
</table>


<h2>Filintegritet</h2>

<table>
    <tr>
        <th>Status</th>
        <td>
            <?php if (
                $fileIntegrity['missingCount'] === 0 &&
                $fileIntegrity['orphanCount'] === 0
            ): ?>
                OK
            <?php else: ?>
                VARNING
            <?php endif; ?>
        </td>
    </tr>

    <tr>
        <th>Registrerade filer</th>
        <td>
            <?= htmlspecialchars(
                (string) $fileIntegrity['registeredCount']
            ) ?>
        </td>
    </tr>

    <tr>
        <th>Fysiska filer</th>
        <td>
            <?= htmlspecialchars(
                (string) $fileIntegrity['physicalCount']
            ) ?>
        </td>
    </tr>

    <tr>
        <th>Saknas på disk</th>
        <td>
            <?= htmlspecialchars(
                (string) $fileIntegrity['missingCount']
            ) ?>
        </td>
    </tr>

    <tr>
        <th>Finns på disk men saknas i databasen</th>
        <td>
            <?= htmlspecialchars(
                (string) $fileIntegrity['orphanCount']
            ) ?>
        </td>
    </tr>
</table>


<?php if ($fileIntegrity['missingCount'] > 0): ?>

    <h3>Saknade filer</h3>

    <ul>
        <?php foreach (
            $fileIntegrity['missingFiles']
            as $filename
        ): ?>

            <li>
                <?= htmlspecialchars($filename) ?>
            </li>

        <?php endforeach; ?>
    </ul>

<?php endif; ?>


<?php if ($fileIntegrity['orphanCount'] > 0): ?>

    <h3>Oregistrerade filer</h3>

    <ul>
        <?php foreach (
            $fileIntegrity['orphanFiles']
            as $filename
        ): ?>

            <li>
                <?= htmlspecialchars($filename) ?>
            </li>

        <?php endforeach; ?>
    </ul>

<?php endif; ?>
