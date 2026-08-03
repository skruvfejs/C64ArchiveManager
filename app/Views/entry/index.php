<?php
/** @var object $entry */
/** @var array $releases */
/** @var int $totalReleases */
/** @var int $uniqueImages */
?>

<h1>
    <?= htmlspecialchars($entry->getTitle()) ?>
</h1>


<p>
    <strong>Entry ID:</strong>
    <?= $entry->getId() ?>
</p>


<p>
    <strong>Antal releases:</strong>
    <?= $totalReleases ?>
</p>


<p>
    <strong>Unika diskbilder:</strong>
    <?= $uniqueImages ?>
</p>


<h2>
    Releases
</h2>


<?php foreach ($releases as $item): ?>

<?php
$release = $item['release'];
$files   = $item['files'];
?>


<div class="card">

<h3>
    <a href="/release?id=<?= $release->getId() ?>">
        Release ID:
        <?= $release->getId() ?>
    </a>
</h3>


<?php foreach ($files as $fileItem): ?>

<?php
$file = $fileItem['file'];
?>


<div class="disk-file">

<p>
    <strong>Fil:</strong>
    <?= htmlspecialchars($file->getFilename()) ?>
</p>


<p>
    <strong>Format:</strong>
    <?= htmlspecialchars($file->getFormat()) ?>
</p>


<p>
    <strong>MD5:</strong>
    <?= htmlspecialchars($file->getMd5()) ?>
</p>


<?php if ($fileItem['duplicate']): ?>

<p>
    ⚠ Samma diskimage finns redan i Release ID:
    <?= $fileItem['duplicateOf'] ?>
</p>

<?php endif; ?>


<p>
    <strong>Storlek:</strong>
    <?= number_format($file->getSize()) ?>
    bytes
</p>


<p>
    <strong>Katalogposter:</strong>
    <?= $fileItem['directoryCount'] ?>
</p>


<p>
    <a href="/disk?id=<?= $release->getId() ?>">
        Öppna disk
    </a>
</p>


</div>


<?php endforeach; ?>


</div>


<?php endforeach; ?>

