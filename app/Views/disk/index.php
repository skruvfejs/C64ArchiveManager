<h1><?= $title ?></h1>


<h2>
    Release:
    <?= htmlspecialchars($release->getName()) ?>
</h2>


<?php foreach ($files as $file): ?>

    <div>

        <h3>
            <?= htmlspecialchars($file->getFilename()) ?>
            (<?= htmlspecialchars($file->getFormat()) ?>)
        </h3>


        <table border="1" cellpadding="5">

            <tr>
                <th>Disk name</th>
                <td>
                    <?= htmlspecialchars(
                        $file->getDiskName() ?? ''
                    ) ?>
                </td>
            </tr>

            <tr>
                <th>Disk ID</th>
                <td>
                    <?= htmlspecialchars(
                        $file->getDiskId() ?? ''
                    ) ?>
                </td>
            </tr>

            <tr>
                <th>Format</th>
                <td>
                    <?= htmlspecialchars(
                        $file->getFormat()
                    ) ?>
                </td>
            </tr>

        </table>


        <br>


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
                        <?= htmlspecialchars(
                            $entry->getFilename()
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $entry->getFiletype()
                        ) ?>
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

    </div>


<?php endforeach; ?>

