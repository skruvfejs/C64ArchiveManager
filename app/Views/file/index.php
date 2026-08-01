<h1><?= $title ?></h1>


<h2>
    File details
</h2>


<table border="1" cellpadding="5">

    <tr>
        <th>Filename</th>
        <td>
            <?= htmlspecialchars(
                $entry->getFilename()
            ) ?>
        </td>
    </tr>


    <tr>
        <th>Type</th>
        <td>
            <?= htmlspecialchars(
                $entry->getFiletype()
            ) ?>
        </td>
    </tr>


    <tr>
        <th>Directory position</th>
        <td>
            <?= $entry->getDirectoryPosition() ?>
        </td>
    </tr>


    <tr>
        <th>Start track</th>
        <td>
            <?= $entry->getStartTrack() ?>
        </td>
    </tr>


    <tr>
        <th>Start sector</th>
        <td>
            <?= $entry->getStartSector() ?>
        </td>
    </tr>


    <tr>
        <th>Blocks</th>
        <td>
            <?= $entry->getBlocks() ?>
        </td>
    </tr>

</table>

