<?php if (!empty($integrity)): ?>

<h3>
    Disk integrity
</h3>

<table border="1" cellpadding="5">

<tr>
    <th>Status</th>
    <td>
        <?php if ($integrity['valid']): ?>
            <?php if (!$integrity['valid']): ?>
                INVALID
            <?php elseif (($integrity['total_orphan_sectors'] ?? 0) > 0): ?>
                VALID WITH ORPHAN SECTORS
            <?php else: ?>
                VALID
            <?php endif; ?>
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

<br>

<?php if (!empty($comparison['_summary'])): ?>

<h4>BAM Statistics</h4>

<table border="1" cellpadding="5">

<tr>
    <th>BAM used sectors</th>
    <td><?= $comparison['_summary']['bam_used'] ?></td>
</tr>

<tr>
    <th>File used sectors</th>
    <td><?= $comparison['_summary']['calculated_used'] ?></td>
</tr>

<tr>
    <th>Unreferenced sectors</th>
    <td><?= $comparison['_summary']['difference'] ?></td>
</tr>

</table>

<br>

<?php endif; ?>

<?php if (!empty($diskMap)): ?>

<h4>Disk Map</h4>

<pre>
<?php foreach ($diskMap as $track => $map): ?>
<?= sprintf('%02d', $track) ?> <?= $map . PHP_EOL ?>
<?php endforeach; ?>
</pre>

<p>
<strong>█</strong> Used<br>
<strong>▒</strong> Unreferenced<br>
<strong>░</strong> Free
</p>

<br>

<?php endif; ?>

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
        <?= count(
            $directories[$file->getId()] ?? []
        ) ?>
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
