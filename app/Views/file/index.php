<?php if (!empty($releaseId)): ?>

<p>
    <a href="/disk?id=<?= $releaseId ?>">
        ← <?= htmlspecialchars(
            $language->get('back_to_disk_explorer')
        ) ?>
    </a>
</p>

<?php endif; ?>


<h1><?= htmlspecialchars($title) ?></h1>


<h2>
    <?= htmlspecialchars(
        $language->get('c64_file_details')
    ) ?>
</h2>


<table border="1" cellpadding="5">

<tr>
    <th>
        <?= htmlspecialchars(
            $language->get('filename')
        ) ?>
    </th>

    <td>
        <?= htmlspecialchars(
            $entry->getFilename()
        ) ?>
    </td>
</tr>


<tr>
    <th>
        <?= htmlspecialchars(
            $language->get('type')
        ) ?>
    </th>

    <td>
        <?= htmlspecialchars(
            $entry->getFiletype()
        ) ?>
    </td>
</tr>


<?php if ($releaseFile !== null): ?>

<tr>
    <th>
        <?= htmlspecialchars(
            $language->get('disk')
        ) ?>
    </th>

    <td>
        <?= htmlspecialchars(
            $releaseFile->getFilename()
        ) ?>
    </td>
</tr>


<tr>
    <th>
        <?= htmlspecialchars(
            $language->get('format')
        ) ?>
    </th>

    <td>
        <?= htmlspecialchars(
            $releaseFile->getFormat()
        ) ?>
    </td>
</tr>


<tr>
    <th>
        <?= htmlspecialchars(
            $language->get('disk_name')
        ) ?>
    </th>

    <td>
        <?= htmlspecialchars(
            $releaseFile->getDiskName() ?? ''
        ) ?>
    </td>
</tr>


<tr>
    <th>
        <?= htmlspecialchars(
            $language->get('disk_id')
        ) ?>
    </th>

    <td>
        <?= htmlspecialchars(
            $releaseFile->getDiskId() ?? ''
        ) ?>
    </td>
</tr>

<?php endif; ?>


<tr>
    <th>
        <?= htmlspecialchars(
            $language->get('directory_position')
        ) ?>
    </th>

    <td>
        <?= $entry->getDirectoryPosition() ?>
    </td>
</tr>


<tr>
    <th>
        <?= htmlspecialchars(
            $language->get('start_track')
        ) ?>
    </th>

    <td>
        <?= $entry->getStartTrack() ?>
    </td>
</tr>


<tr>
    <th>
        <?= htmlspecialchars(
            $language->get('start_sector')
        ) ?>
    </th>

    <td>
        <?= $entry->getStartSector() ?>
    </td>
</tr>


<tr>
    <th>
        <?= htmlspecialchars(
            $language->get('blocks')
        ) ?>
    </th>

    <td>
        <?= $entry->getBlocks() ?>
    </td>
</tr>


<tr>
    <th>
        <?= htmlspecialchars(
            $language->get('estimated_size')
        ) ?>
    </th>

    <td>
        <?= $entry->getBlocks() * 254 ?>

        <?= htmlspecialchars(
            $language->get('bytes')
        ) ?>
    </td>
</tr>


</table>


<br>


<a href="/file/download?id=<?= $entry->getId() ?>">
    <?= htmlspecialchars(
        $language->get('download_file')
    ) ?>
</a>
