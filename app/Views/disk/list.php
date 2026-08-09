<?php

declare(strict_types=1);

?>


<h2>
    <?= htmlspecialchars(
        $language->get('disks')
    ) ?>
</h2>



<form method="get" action="/disk">

    <label>
        <?= htmlspecialchars(
            $language->get('search')
        ) ?>:
    </label>

    <input
        type="text"
        name="search"
        value="<?= htmlspecialchars($search ?? '') ?>"
    >


    <button type="submit">
        <?= htmlspecialchars(
            $language->get('search')
        ) ?>
    </button>


    <?php if (!empty($search)): ?>

        <a href="/disk">
            <?= htmlspecialchars(
                $language->get('clear')
            ) ?>
        </a>

    <?php endif; ?>

</form>



<p>

    <?php if (
        $authorization->can(
            \App\Core\Permission::IMPORT
        )
    ): ?>

        <a href="/import">
            <?= htmlspecialchars(
                $language->get('import_new_release')
            ) ?>
        </a>

        |

    <?php endif; ?>


    <?php if (
        $authorization->can(
            \App\Core\Permission::VIEW_LOGS
        )
    ): ?>

        <a href="/import/logs">
            <?= htmlspecialchars(
                $language->get('import_log')
            ) ?>
        </a>

    <?php endif; ?>

</p>


<?php if (($total ?? 0) > 0): ?>

<p>

    <?= htmlspecialchars(
        $language->get('showing')
    ) ?>

    <?= (($page ?? 1) - 1) * ($perPage ?? 50) + 1 ?>


    -


    <?= min(
        ($page ?? 1) * ($perPage ?? 50),
        $total
    ) ?>


    <?= htmlspecialchars(
        $language->get('of')
    ) ?>


    <?= $total ?>

    <?= htmlspecialchars(
        $language->get('disks')
    ) ?>

</p>

<?php endif; ?>



<?php if (empty($disks)): ?>

<p>
    <?= htmlspecialchars(
        $language->get('no_disks_found')
    ) ?>
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

                    <?= htmlspecialchars(
                        $language->get('title')
                    ) ?>

                </a>

            </th>



            <th>
                <?= htmlspecialchars(
                    $language->get('disk_name')
                ) ?>
            </th>



            <th>
                <?= htmlspecialchars(
                    $language->get('disk_id')
                ) ?>
            </th>



            <th>

                <a href="/disk?search=<?= urlencode($search ?? '') ?>&sort=filename">

                    <?= htmlspecialchars(
                        $language->get('filename')
                    ) ?>

                </a>

            </th>



            <th>

                <a href="/disk?search=<?= urlencode($search ?? '') ?>&sort=format">

                    <?= htmlspecialchars(
                        $language->get('format')
                    ) ?>

                </a>

            </th>



            <th>

                <a href="/disk?search=<?= urlencode($search ?? '') ?>&sort=size">

                    <?= htmlspecialchars(
                        $language->get('size')
                    ) ?>

                </a>

            </th>



            <th>
                MD5
            </th>



            <th>
                <?= htmlspecialchars(
                    $language->get('comment')
                ) ?>
            </th>



            <th>
                <?= htmlspecialchars(
                    $language->get('action')
                ) ?>
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

                <?= htmlspecialchars(
                    $language->get('bytes')
                ) ?>

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

                    <?= htmlspecialchars(
                        $language->get('view')
                    ) ?>

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

    <?= htmlspecialchars(
        $language->get('previous')
    ) ?>

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

    <?= htmlspecialchars(
        $language->get('next')
    ) ?>

</a>

<?php endif; ?>


</p>

<?php endif; ?>
