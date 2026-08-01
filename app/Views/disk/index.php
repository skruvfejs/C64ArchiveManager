<h1><?= $title ?></h1>

<h2>
    Release:
    <?= htmlspecialchars($release->getName()) ?>
</h2>

<?php foreach ($files as $file): ?>

    <h3>
        <?= htmlspecialchars($file->getFilename()) ?>
        (<?= htmlspecialchars($file->getFormat()) ?>)
    </h3>

    <table border="1" cellpadding="5">

        <tr>
            <th>Filename</th>
            <th>Type</th>
            <th>Track</th>
            <th>Sector</th>
            <th>Blocks</th>
        </tr>

        <?php foreach ($directories[$file->getId()] ?? [] as $entry): ?>

            <tr>
                <td>
                    <?= htmlspecialchars($entry->getFilename()) ?>
                </td>

                <td>
                    <?= htmlspecialchars($entry->getFiletype()) ?>
                </td>

                <td>
                    <?= $entry->getStartTrack() ?>
                </td>

                <td>
                    <?= $entry->getStartSector() ?>
                </td>

                <td>
                    <?= $entry->getBlocks() ?>
                </td>
            </tr>

        <?php endforeach; ?>

    </table>

<?php endforeach; ?>

