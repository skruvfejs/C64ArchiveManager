<?php if (!empty($release->getNotes())): ?>

<h3>
    <?= htmlspecialchars(
        $language->get('comment')
    ) ?>
</h3>

<p>
    <?= nl2br(
        htmlspecialchars(
            $release->getNotes()
        )
    ) ?>
</p>

<br>

<?php endif; ?>



<?php if (!empty($integrity)): ?>

<h3>
    <?= htmlspecialchars(
        $language->get('disk_integrity')
    ) ?>
</h3>


<table border="1" cellpadding="5">

<tr>
    <th>
        <?= htmlspecialchars(
            $language->get('status')
        ) ?>
    </th>

    <td>

        <?php if ($integrity['valid']): ?>

            <?php if (($integrity['total_orphan_sectors'] ?? 0) > 0): ?>

                <?= htmlspecialchars(
                    $language->get('valid_with_orphan_sectors')
                ) ?>

            <?php else: ?>

                <?= htmlspecialchars(
                    $language->get('valid')
                ) ?>

            <?php endif; ?>

        <?php else: ?>

            <?= htmlspecialchars(
                $language->get('invalid')
            ) ?>

        <?php endif; ?>

    </td>
</tr>


<tr>

    <th>
        <?= htmlspecialchars(
            $language->get('orphan_sectors')
        ) ?>
    </th>

    <td>
        <?= $integrity['total_orphan_sectors'] ?>
    </td>

</tr>


</table>


<br>



<?php if (!empty($comparison['_summary'])): ?>

<h4>
    <?= htmlspecialchars(
        $language->get('bam_statistics')
    ) ?>
</h4>


<table border="1" cellpadding="5">

<tr>
    <th>
        <?= htmlspecialchars(
            $language->get('bam_used_sectors')
        ) ?>
    </th>

    <td>
        <?= $comparison['_summary']['bam_used'] ?>
    </td>
</tr>


<tr>
    <th>
        <?= htmlspecialchars(
            $language->get('file_used_sectors')
        ) ?>
    </th>

    <td>
        <?= $comparison['_summary']['calculated_used'] ?>
    </td>
</tr>


<tr>
    <th>
        <?= htmlspecialchars(
            $language->get('unreferenced_sectors')
        ) ?>
    </th>

    <td>
        <?= $comparison['_summary']['difference'] ?>
    </td>
</tr>


</table>


<br>


<?php endif; ?>


<?php endif; ?>



<?php if (!empty($diskMap)): ?>

<h4>
    <?= htmlspecialchars(
        $language->get('disk_map')
    ) ?>
</h4>


<pre>
<?php foreach ($diskMap as $track => $map): ?>

<?= sprintf('%02d', $track) ?> <?= $map . PHP_EOL ?>

<?php endforeach; ?>
</pre>


<p>

<strong>█</strong>
<?= htmlspecialchars(
    $language->get('used')
) ?><br>

<strong>▒</strong>
<?= htmlspecialchars(
    $language->get('unreferenced')
) ?><br>

<strong>░</strong>
<?= htmlspecialchars(
    $language->get('free')
) ?>

</p>


<br>


<?php endif; ?>

<?php if (!empty($integrity['warnings'])): ?>

<h4>
    <?= htmlspecialchars(
        $language->get('warnings')
    ) ?>
</h4>


<ul>

<?php foreach ($integrity['warnings'] as $warning): ?>

<li>
    <?= htmlspecialchars($warning) ?>
</li>

<?php endforeach; ?>

</ul>


<?php endif; ?>




<?php foreach ($files as $file): ?>


<h3>

    <?= htmlspecialchars(
        $file->getFilename()
    ) ?>

    (<?= htmlspecialchars(
        $file->getFormat()
    ) ?>)

</h3>



<table border="1" cellpadding="5">


<tr>

    <th>
        <?= htmlspecialchars(
            $language->get('disk_name')
        ) ?>
    </th>

    <td>

        <?= htmlspecialchars(
            $file->getDiskName() ?? ''
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
            $file->getDiskId() ?? ''
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
            $file->getFormat()
        ) ?>

    </td>

</tr>



<tr>

    <th>
        <?= htmlspecialchars(
            $language->get('files_on_disk')
        ) ?>
    </th>

    <td>

        <?= count(
            $directories[$file->getId()] ?? []
        ) ?>

    </td>

</tr>



<tr>

    <th>
        <?= htmlspecialchars(
            $language->get('size')
        ) ?>
    </th>

    <td>

        <?= $file->getSize() ?>

        <?= htmlspecialchars(
            $language->get('bytes')
        ) ?>

    </td>

</tr>


</table>



<p>

    <a href="/disk/directory?id=<?= $release->getId() ?>">

        <?= htmlspecialchars(
            $language->get('c64_directory')
        ) ?>

    </a>

</p>


<br>



<table border="1" cellpadding="5">


<tr>

    <th>#</th>

    <th>
        <?= htmlspecialchars(
            $language->get('filename')
        ) ?>
    </th>

    <th>
        <?= htmlspecialchars(
            $language->get('type')
        ) ?>
    </th>

    <th>
        <?= htmlspecialchars(
            $language->get('track')
        ) ?>
    </th>

    <th>
        <?= htmlspecialchars(
            $language->get('sector')
        ) ?>
    </th>

    <th>
        <?= htmlspecialchars(
            $language->get('blocks')
        ) ?>
    </th>

    <th>
        <?= htmlspecialchars(
            $language->get('status')
        ) ?>
    </th>

</tr>



<?php foreach (
    $directories[$file->getId()] ?? []
    as $entry
): ?>


<tr>


<td>

    <?= $entry->getDirectoryPosition() ?>

</td>


<td>

    <a href="/file?id=<?= $entry->getId() ?>">

        <?= htmlspecialchars(
            $entry->getFilename()
        ) ?>

    </a>

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


<td>


<?php if ($entry->isLocked()): ?>

    <?= htmlspecialchars(
        $language->get('locked')
    ) ?>


<?php elseif ($entry->isClosed()): ?>

    <?= htmlspecialchars(
        $language->get('closed')
    ) ?>


<?php else: ?>

    <?= htmlspecialchars(
        $language->get('open')
    ) ?>


<?php endif; ?>


</td>


</tr>


<?php endforeach; ?>


</table>


<?php endforeach; ?>
