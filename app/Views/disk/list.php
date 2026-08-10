<?php

declare(strict_types=1);
?>
<style>
    /*
     * Disk list page:
     * Use the available desktop width so the disk table
     * remains inside the page container.
     */
    .disk-page-layout {
        width: 100%;
    }

    .disk-page-layout table {
        width: 100%;
        max-width: 100%;
    }

    @media (min-width: 1250px) {
        body .container {
            max-width: 1600px;
            width: calc(100% - 40px);
        }
    }
</style>

<?php

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

                <a href="/disk?search=<?= urlencode($search ?? '') ?>&tag=<?= (int) ($tagId ?? 0) ?>&sort=id">

                    ID

                </a>

            </th>



            <th>

                <a href="/disk?search=<?= urlencode($search ?? '') ?>&tag=<?= (int) ($tagId ?? 0) ?>&sort=title">

                    <?= htmlspecialchars(
                        $language->get('title')
                    ) ?>

                </a>

            </th>



            <th>
                <a href="/disk?search=<?= urlencode($search ?? '') ?>&tag=<?= (int) ($tagId ?? 0) ?>&sort=disk_name">
                    <?= htmlspecialchars(
                        $language->get('disk_name')
                    ) ?>
                </a>
            </th>



            <th>
                <a href="/disk?search=<?= urlencode($search ?? '') ?>&tag=<?= (int) ($tagId ?? 0) ?>&sort=disk_id">
                    <?= htmlspecialchars(
                        $language->get('disk_id')
                    ) ?>
                </a>
            </th>
            <th>
                <a href="/disk?search=<?= urlencode($search ?? '') ?>&tag=<?= (int) ($tagId ?? 0) ?>&sort=tags">
                    <?= htmlspecialchars(
                        $language->get('tags')
                    ) ?>
                </a>
            </th>




            <th>

                <a href="/disk?search=<?= urlencode($search ?? '') ?>&tag=<?= (int) ($tagId ?? 0) ?>&sort=filename">

                    <?= htmlspecialchars(
                        $language->get('filename')
                    ) ?>

                </a>

            </th>



            <th>

                <a href="/disk?search=<?= urlencode($search ?? '') ?>&tag=<?= (int) ($tagId ?? 0) ?>&sort=format">

                    <?= htmlspecialchars(
                        $language->get('format')
                    ) ?>

                </a>

            </th>



            <th>

                <a href="/disk?search=<?= urlencode($search ?? '') ?>&tag=<?= (int) ($tagId ?? 0) ?>&sort=size">

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
                <?php foreach (
                    $diskTags[$disk->getId()] ?? []
                    as $tag
                ): ?>

                    <?php
                    $tagName = '';

                    foreach ($allTags ?? [] as $availableTag) {
                        if (
                            $availableTag->getId()
                            === $tag->getTagId()
                        ) {
                            $tagName =
                                $availableTag->getName();

                            break;
                        }
                    }
                    ?>

                    <a href="/disk?search=<?= urlencode($search ?? '') ?>&tag=<?= (int) $tag->getTagId() ?>">
                        <?= htmlspecialchars(
                            $tagName
                        ) ?>
                    </a>

                <?php endforeach; ?>
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

<a href="/disk?search=<?= urlencode($search ?? '') ?>&tag=<?= (int) ($tagId ?? 0) ?>&sort=<?= urlencode($sort ?? 'id') ?>&page=<?= $page - 1 ?>">

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

<a href="/disk?search=<?= urlencode($search ?? '') ?>&tag=<?= (int) ($tagId ?? 0) ?>&sort=<?= urlencode($sort ?? 'id') ?>&page=<?= $i ?>">

    <?= $i ?>

</a>


<?php endif; ?>


<?php endfor; ?>



<?php if (($page ?? 1) < $pages): ?>

<a href="/disk?search=<?= urlencode($search ?? '') ?>&tag=<?= (int) ($tagId ?? 0) ?>&sort=<?= urlencode($sort ?? 'id') ?>&page=<?= $page + 1 ?>">

    <?= htmlspecialchars(
        $language->get('next')
    ) ?>

</a>

<?php endif; ?>


</p>

<?php endif; ?>
