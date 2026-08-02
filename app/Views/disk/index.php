<?php
$currentSort = $sort ?? '';
?>


<form method="get" action="/disk">

    <input
        type="hidden"
        name="id"
        value="<?= $release->getId() ?>"
    >


    <label>
        Search:
    </label>


    <input
        type="text"
        name="search"
        value="<?= htmlspecialchars($search ?? '') ?>"
    >


    <label>
        Sort:
    </label>


    <select name="sort">

        <option value=""
            <?= $currentSort === '' ? 'selected' : '' ?>>
            Original order
        </option>


        <option value="name"
            <?= $currentSort === 'name' ? 'selected' : '' ?>>
            Filename
        </option>


        <option value="blocks"
            <?= $currentSort === 'blocks' ? 'selected' : '' ?>>
            Blocks
        </option>


        <option value="track"
            <?= $currentSort === 'track' ? 'selected' : '' ?>>
            Track
        </option>

    </select>


    <button type="submit">
        Search
    </button>


    <?php if (!empty($search) || !empty($sort)): ?>

        <a href="/disk?id=<?= $release->getId() ?>">
            Clear
        </a>

    <?php endif; ?>

</form>


<br>


<h1><?= $title ?></h1>


<h2>
    Release:
    <?= htmlspecialchars($release->getName()) ?>
</h2>


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
                <?= $file->getSize() ?> bytes
            </td>
        </tr>

    </table>


    <br>


    <table border="1" cellpadding="5">

        <tr>
            <th>#</th>
            <th>Filename</th>
            <th>Type</th>
            <th>Track</th>
            <th>Sector</th>
            <th>Blocks</th>
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

            </tr>

        <?php endforeach; ?>


    </table>


<?php endforeach; ?>

