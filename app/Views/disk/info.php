<h1><?= $title ?></h1>


<h2>
    <?= htmlspecialchars($release->getName()) ?>
</h2>


<pre>

<?= htmlspecialchars(
    $language->get('disk')
) ?>:
<?= htmlspecialchars(
    $file->getFilename()
) ?>


<?= htmlspecialchars(
    $language->get('format')
) ?>:
<?= htmlspecialchars(
    $format
) ?>


<?= htmlspecialchars(
    $language->get('type')
) ?>:
<?= htmlspecialchars(
    $diskType
) ?>


<?= htmlspecialchars(
    $language->get('tracks')
) ?>:
<?php if ($isT64): ?>
N/A
<?php else: ?>
<?= $tracks ?>
<?php endif; ?>


<?= htmlspecialchars(
    $language->get('files')
) ?>:
<?= $fileCount ?>


<?php if ($isT64): ?>

<?= htmlspecialchars(
    $language->get('size')
) ?>:
<?= $fileSize ?>
<?= htmlspecialchars(
    $language->get('bytes')
) ?>


<?php else: ?>

<?= htmlspecialchars(
    $language->get('blocks')
) ?>:
<?= $blocksUsed ?> / <?= $totalBlocks ?>


<?= htmlspecialchars(
    $language->get('free')
) ?>:
<?= $blocksFree ?>

<?php endif; ?>


</pre>


<p>
    <a href="/disk/directory?id=<?= $release->getId() ?>">
        ← <?= htmlspecialchars(
            $language->get('back_to_directory')
        ) ?>
    </a>
</p>
