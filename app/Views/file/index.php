<?php if (!empty($releaseId)): ?>

<p>
    <a href="/disk?id=<?= $releaseId ?>">
        ← Back to Disk Explorer
    </a>
</p>

<?php endif; ?>


<h1><?= $title ?></h1>


<h2>
    File details
</h2>


<table border="1" cellpadding="5">

    <tr>
        <th>Filename</th>
        <td>
            <?= htmlspecialchars(
                $entry->getFilename()
            ) ?>
        </td>
    </tr>


    <tr>
        <th>Type</th>
        <td>
            <?= htmlspecialchars(
                $entry->getFiletype()
            ) ?>
        </td>
    </tr>


    <?php if ($releaseFile !== null): ?>

    <tr>
        <th>Disk</th>
        <td>
            <?= htmlspecialchars(
                $releaseFile->getFilename()
            ) ?>
        </td>
    </tr>


    <tr>
        <th>Format</th>
        <td>
            <?= htmlspecialchars(
                $releaseFile->getFormat()
            ) ?>
        </td>
    </tr>


    <tr>
        <th>Disk name</th>
        <td>
            <?= htmlspecialchars(
                $releaseFile->getDiskName() ?? ''
            ) ?>
        </td>
    </tr>


    <tr>
        <th>Disk ID</th>
        <td>
            <?= htmlspecialchars(
                $releaseFile->getDiskId() ?? ''
            ) ?>
        </td>
    </tr>


    <tr>
        <th>Size</th>
        <td>
            <?= $releaseFile->getSize() ?> bytes
        </td>
    </tr>

    <?php endif; ?>


    <tr>
        <th>Directory position</th>
        <td>
            <?= $entry->getDirectoryPosition() ?>
        </td>
    </tr>


    <tr>
        <th>Start track</th>
        <td>
            <?= $entry->getStartTrack() ?>
        </td>
    </tr>


    <tr>
        <th>Start sector</th>
        <td>
            <?= $entry->getStartSector() ?>
        </td>
    </tr>


    <tr>
        <th>Blocks</th>
        <td>
            <?= $entry->getBlocks() ?>
        </td>
    </tr>

</table>

