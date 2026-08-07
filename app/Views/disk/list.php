<?php

declare(strict_types=1);

?>


<h2>
    <?= htmlspecialchars($title ?? 'Diskar') ?>
</h2>



<form method="get" action="/disk">

    <label>
        Sök:
    </label>

    <input
        type="text"
        name="search"
        value="<?= htmlspecialchars($search ?? '') ?>"
    >


    <button type="submit">
        Sök
    </button>


    <?php if (!empty($search)): ?>

        <a href="/disk">
            Rensa
        </a>

    <?php endif; ?>

</form>



<p>

    <a href="/import">
        Importera ny release
    </a>

    |

    <a href="/import/logs">
        Importlogg
    </a>

</p>



<?php if (empty($disks)): ?>

<p>
    Inga diskar hittades.
</p>


<?php else: ?>


<table border="1" cellpadding="5" cellspacing="0">

    <thead>

        <tr>

            <th>
                <a href="/disk?sort=id">
                    ID
                </a>
            </th>



            <th>
                <a href="/disk?search=<?= urlencode($search ?? '') ?>&sort=title">
                    Titel
                </a>
            </th>



            <th>
                Disknamn
            </th>



            <th>
                Disk-ID
            </th>



            <th>
                <a href="/disk?search=<?= urlencode($search ?? '') ?>&sort=filename">
                    Filnamn
                </a>
            </th>



            <th>
                <a href="/disk?search=<?= urlencode($search ?? '') ?>&sort=format">
                    Format
                </a>
            </th>



            <th>
                <a href="/disk?search=<?= urlencode($search ?? '') ?>&sort=size">
                    Storlek
                </a>
            </th>



            <th>
                MD5
            </th>



            <th>
                Kommentar
            </th>



            <th>
                Åtgärd
            </th>

        </tr>

    </thead>



    <tbody>

    <tbody>


    <?php foreach ($disks as $disk): ?>


        <tr>


            <td>

                <?= htmlspecialchars(
                    (string) $disk->getId()
                ) ?>

            </td>



            <td>

                <?= htmlspecialchars(
                    $disk->getEntryTitle() ?? ''
                ) ?>

            </td>



            <td>

                <?= htmlspecialchars(
                    $disk->getDiskName() ?? ''
                ) ?>

            </td>



            <td>

                <?= htmlspecialchars(
                    $disk->getDiskId() ?? ''
                ) ?>

            </td>



            <td>

                <?= htmlspecialchars(
                    $disk->getFilename()
                ) ?>

            </td>



            <td>

                <?= htmlspecialchars(
                    $disk->getFormat()
                ) ?>

            </td>



            <td>

                <?= htmlspecialchars(
                    (string) $disk->getSize()
                ) ?>

                bytes

            </td>



            <td>

                <?= htmlspecialchars(
                    $disk->getMd5()
                ) ?>

            </td>



            <td>

                <?= htmlspecialchars(
                    $disk->getReleaseNotes() ?? ''
                ) ?>

            </td>



            <td>

                <a href="/disk?id=<?= $disk->getId() ?>">

                    Visa

                </a>

            </td>


        </tr>


    <?php endforeach; ?>


    </tbody>


</table>


<?php endif; ?>
