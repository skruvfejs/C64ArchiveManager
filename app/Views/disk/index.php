<?php if (!empty($integrity)): ?>

<h3>
    Disk integrity
</h3>

<table border="1" cellpadding="5">

<tr>
    <th>Status</th>
    <td>
        <?php if ($integrity['valid']): ?>
            VALID
        <?php else: ?>
            INVALID
        <?php endif; ?>
    </td>
</tr>


<tr>
    <th>Orphan sectors</th>
    <td>
        <?= $integrity['total_orphan_sectors'] ?>
    </td>
</tr>

</table>


<?php if (!empty($integrity['warnings'])): ?>

<h4>
    Warnings
</h4>

<ul>

<?php foreach ($integrity['warnings'] as $warning): ?>

<li>
    <?= htmlspecialchars($warning) ?>
</li>

<?php endforeach; ?>

</ul>

<?php endif; ?>


<br>

<?php endif; ?>


<?php if (!empty($trackUsage)): ?>

<h3>
    Track usage
</h3>


<table border="1" cellpadding="5">

<tr>
    <th>Track</th>
    <th>Used blocks</th>
</tr>


<?php foreach ($trackUsage as $track => $blocks): ?>

<tr>

<td>
    <?= $track ?>
</td>

<td>
    <?= $blocks ?>
</td>

</tr>


<?php endforeach; ?>


</table>

<br>

<?php endif; ?>



<?php if (!empty($trackLayout)): ?>

<h3>
    Track layout
</h3>


<table border="1" cellpadding="5">

<tr>
    <th>Track</th>
    <th>Used</th>
    <th>Free</th>
    <th>Total</th>
</tr>


<?php foreach ($trackLayout as $track => $data): ?>


<tr>

<td>
    <?= $track ?>
</td>


<td>
    <?= $data['used'] ?>
</td>


<td>
    <?= $data['total'] - $data['used'] ?>
</td>


<td>
    <?= $data['total'] ?>
</td>

</tr>


<?php endforeach; ?>


</table>


<br>

<?php endif; ?>



<?php foreach ($files as $file): ?>


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


<tr>
    <th>Files on disk</th>

    <td>
        <?= count($directories[$file->getId()] ?? []) ?>
    </td>
</tr>


<tr>
    <th>Size</th>

    <td>
        <?= $file->getSize() ?>
        bytes
    </td>
</tr>


</table>


<p>
    <a href="/disk/directory?id=<?= $release->getId() ?>">
        C64 Directory
    </a>
</p>


<br>



<table border="1" cellpadding="5">


<tr>
    <th>#</th>
    <th>Filename</th>
    <th>Type</th>
    <th>Track</th>
    <th>Sector</th>
    <th>Blocks</th>
    <th>Status</th>
</tr>



<?php foreach ($directories[$file->getId()] ?? [] as $entry): ?>


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

    LOCKED

<?php elseif ($entry->isClosed()): ?>

    CLOSED

<?php else: ?>

    OPEN

<?php endif; ?>

</td>


</tr>


<?php endforeach; ?>


</table>


<?php endforeach; ?>
