<?php
/** @var object $release */
/** @var object|null $entry */
/** @var array $files */
?>


<h1>
    Release
</h1>


<h2>
    <?= htmlspecialchars($release->getName()) ?>
</h2>


<p>
    <strong>Release ID:</strong>
    <?= $release->getId() ?>
</p>


<?php if ($entry !== null): ?>

<p>
    <a href="/entry?id=<?= $entry->getId() ?>">
        ← Tillbaka till Entry:
        <?= htmlspecialchars($entry->getTitle()) ?>
    </a>
</p>

<?php endif; ?>


<h2>
    Disk images
</h2>


<?php foreach ($files as $fileItem): ?>

<?php
$file = $fileItem['file'];
$duplicates = $fileItem['md5Duplicates'] ?? [];
?>


<div class="card">


<h3>
    <?= htmlspecialchars($file->getFilename()) ?>
</h3>


<table border="1" cellpadding="5">


<tr>
    <th>Filename</th>
    <td>
        <?= htmlspecialchars($file->getFilename()) ?>
    </td>
</tr>


<tr>
    <th>Format</th>
    <td>
        <?= htmlspecialchars($file->getFormat()) ?>
    </td>
</tr>


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
    <th>MD5</th>
    <td>
        <?= htmlspecialchars(
            $file->getMd5() ?? ''
        ) ?>


        <?php if (!empty($duplicates)): ?>

        <br><br>

        <details>

            <summary>
                ⚠ Samma diskimage finns även på
                <?= count($duplicates) ?>
                andra ställen
            </summary>


            <ul>

            <?php foreach ($duplicates as $duplicate): ?>

                <li>

                <?php if ($duplicate['entry'] !== null): ?>

                    Entry:
                    <a href="/entry?id=<?= $duplicate['entry']->getId() ?>">
                        <?= htmlspecialchars(
                            $duplicate['entry']->getTitle()
                        ) ?>
                    </a>
                    <br>

                <?php endif; ?>


                Release ID:
                <a href="/release?id=<?= $duplicate['release']->getId() ?>">
                    <?= $duplicate['release']->getId() ?>
                </a>

                <br>


                Fil:
                <?= htmlspecialchars(
                    $duplicate['file']->getFilename()
                ) ?>

                </li>

            <?php endforeach; ?>

            </ul>


        </details>

        <?php endif; ?>

    </td>
</tr>


<tr>
    <th>Size</th>
    <td>
        <?= number_format(
            $file->getSize()
        ) ?>
        bytes
    </td>
</tr>


<tr>
    <th>Directory entries</th>
    <td>
        <?= $fileItem['directoryCount'] ?>
    </td>
</tr>


</table>


<p>
    <a href="/disk?id=<?= $release->getId() ?>">
        Öppna disk
    </a>
</p>


</div>


<?php endforeach; ?>

