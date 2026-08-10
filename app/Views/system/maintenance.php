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


<h2>Databasfilkontroll</h2>

<table>
    <tr>
        <th>Status</th>
        <td>
            <?php if (
                $databaseFileIntegrity['missingPathCount'] === 0 &&
                $databaseFileIntegrity['sizeMismatchCount'] === 0
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
                (string) $databaseFileIntegrity['registeredCount']
            ) ?>
        </td>
    </tr>

    <tr>
        <th>Giltig fysisk sökväg</th>
        <td>
            <?= htmlspecialchars(
                (string) $databaseFileIntegrity['validPathCount']
            ) ?>
        </td>
    </tr>

    <tr>
        <th>Saknad fysisk fil</th>
        <td>
            <?= htmlspecialchars(
                (string) $databaseFileIntegrity['missingPathCount']
            ) ?>
        </td>
    </tr>

    <tr>
        <th>Storleksfel</th>
        <td>
            <?= htmlspecialchars(
                (string) $databaseFileIntegrity['sizeMismatchCount']
            ) ?>
        </td>
    </tr>
</table>


<?php if (
    $databaseFileIntegrity['missingPathCount'] > 0
): ?>

    <h3>Saknade fysiska filer</h3>

    <ul>
        <?php foreach (
            $databaseFileIntegrity['missingPaths']
            as $file
        ): ?>

            <li>
                <?= htmlspecialchars(
                    $file['filename']
                ) ?>

                —
                <?= htmlspecialchars(
                    $file['path']
                ) ?>
            </li>

        <?php endforeach; ?>
    </ul>

<?php endif; ?>


<?php if (
    $databaseFileIntegrity['sizeMismatchCount'] > 0
): ?>

    <h3>Storleksfel</h3>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fil</th>
                <th>Databas</th>
                <th>Fysisk</th>
                <th>Diff</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach (
                $databaseFileIntegrity['sizeMismatches']
                as $file
            ): ?>

                <?php
                $difference =
                    (int) $file['actualSize']
                    -
                    (int) $file['databaseSize'];
                ?>

                <tr>
                    <td>
                        <?= htmlspecialchars(
                            (string) $file['id']
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $file['filename']
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            (string) $file['databaseSize']
                        ) ?>
                        byte
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            (string) $file['actualSize']
                        ) ?>
                        byte
                    </td>

                    <td>
                        <?= $difference >= 0 ? '+' : '' ?><?= htmlspecialchars(
                            (string) $difference
                        ) ?>
                        byte
                    </td>
                </tr>

            <?php endforeach; ?>

        </tbody>
    </table>

<?php endif; ?>
