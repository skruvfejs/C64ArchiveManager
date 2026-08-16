<?php
/** @var object $entry */
/** @var array $entryTags */
/** @var array $allTags */
/** @var array $releases */
/** @var int $totalReleases */
/** @var int $uniqueImages */
?>

    <meta charset="UTF-8">



<div class="print-button">
    <button type="button" onclick="window.print()">
        Skriv ut
    </button>
</div>

<h1>
    <?= htmlspecialchars($entry->getTitle()) ?>
</h1>

<table class="summary">
    <tr>
        <th>Post-ID</th>
        <td>
            <?= (int) $entry->getId() ?>
        </td>
    </tr>

    <tr>
        <th>Antal releaser</th>
        <td>
            <?= (int) $totalReleases ?>
        </td>
    </tr>

    <tr>
        <th>Unika diskbilder</th>
        <td>
            <?= (int) $uniqueImages ?>
        </td>
    </tr>

    <tr>
        <th>Taggar</th>
        <td>
            <?php
            $assignedTagIds = [];

            foreach ($entryTags as $entryTag) {
                $assignedTagIds[] =
                    $entryTag->getTagId();
            }

            $tagNames = [];

            foreach ($allTags as $tag) {
                if (
                    in_array(
                        $tag->getId(),
                        $assignedTagIds,
                        true
                    )
                ) {
                    $tagNames[] =
                        $tag->getName();
                }
            }
            ?>

            <?= htmlspecialchars(
                implode(', ', $tagNames)
            ) ?>
        </td>
    </tr>
</table>

<h2>Releaser</h2>

<?php
usort(
    $releases,
    static function (
        array $a,
        array $b
    ): int {
        return
            $a['release']->getId()
            <=> $b['release']->getId();
    }
);
?>

<?php foreach ($releases as $releaseData): ?>

    <div class="release">

        <h3>
            Release-ID:
            <?= (int) $releaseData['release']->getId() ?>
        </h3>

        <?php foreach ($releaseData['files'] as $fileData): ?>

            <?php $file = $fileData['file']; ?>

            <table>
                <tr>
                    <th>Fil</th>
                    <td>
                        <?= htmlspecialchars(
                            $file->getFilename()
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
                    <th>Disknamn</th>
                    <td>
                        <?= htmlspecialchars(
                            $file->getDiskName() ?? ''
                        ) ?>
                    </td>
                </tr>

                <tr>
                    <th>Disk-ID</th>
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
                    </td>
                </tr>

                <tr>
                    <th>Storlek</th>
                    <td>
                        <?= number_format(
                            $file->getSize()
                        ) ?>
                        bytes
                    </td>
                </tr>

                <tr>
                    <th>Katalogposter</th>
                    <td>
                        <?= (int) $fileData['directoryCount'] ?>
                    </td>
                </tr>
            </table>

        <?php endforeach; ?>

    </div>

<?php endforeach; ?>
