<?php
/** @var object $entry */
/** @var array $releases */
?>

    <meta charset="UTF-8">


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

<div class="print-button">
    <button type="button" onclick="window.print()">
        Skriv ut
    </button>
</div>

<h1>
    <?= htmlspecialchars($entry->getTitle()) ?>
</h1>

<table>
    <thead>
        <tr>
            <th>Release-ID</th>
            <th>Titel</th>
            <th>Fil</th>
            <th>Format</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($releases as $releaseData): ?>

            <?php foreach ($releaseData['files'] as $fileData): ?>

                <?php $file = $fileData['file']; ?>

                <tr>
                    <td>
                        <?= (int) $releaseData['release']->getId() ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $entry->getTitle()
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $file->getFilename()
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $file->getFormat()
                        ) ?>
                    </td>
                </tr>

            <?php endforeach; ?>

        <?php endforeach; ?>
    </tbody>
</table>
