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



<?php if (($total ?? 0) > 0): ?>

<p>

    Visar

    <?= (($page ?? 1) - 1) * ($perPage ?? 50) + 1 ?>


    -


    <?= min(
        ($page ?? 1) * ($perPage ?? 50),
        $total
    ) ?>


    av

    <?= $total ?>

    diskar

</p>

<?php endif; ?>



<?php if (empty($disks)): ?>

<p>
    Inga diskar hittades.
</p>


<?php else: ?>


<table border="1" cellpadding="5" cellspacing="0">

    <thead>

        <tr>

            <th>

                <a href="/disk?search=<?= urlencode($search ?? '') ?>&sort=id">

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



<?php if (($pages ?? 1) > 1): ?>

<p>


<?php if (($page ?? 1) > 1): ?>

<a href="/disk?search=<?= urlencode($search ?? '') ?>&sort=<?= urlencode($sort ?? 'id') ?>&page=<?= $page - 1 ?>">

    Föregående

</a>

<?php endif; ?>



<?php for ($i = 1; $i <= $pages; $i++): ?>


<?php if ($i === ($page ?? 1)): ?>

<strong>

    <?= $i ?>

</strong>


<?php else: ?>

<a href="/disk?search=<?= urlencode($search ?? '') ?>&sort=<?= urlencode($sort ?? 'id') ?>&page=<?= $i ?>">

    <?= $i ?>

</a>


<?php endif; ?>


<?php endfor; ?>



<?php if (($page ?? 1) < $pages): ?>

<a href="/disk?search=<?= urlencode($search ?? '') ?>&sort=<?= urlencode($sort ?? 'id') ?>&page=<?= $page + 1 ?>">

    Nästa

</a>

<?php endif; ?>


</p>

<?php endif; ?>
