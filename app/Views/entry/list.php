<?php

declare(strict_types=1);

?>

<h2>
    <?= htmlspecialchars(
        $language->get('archive')
    ) ?>
</h2>


<form method="get" action="/entry">

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

        <a href="/entry">
            <?= htmlspecialchars(
                $language->get('clear')
            ) ?>
        </a>

    <?php endif; ?>

</form>


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
        $language->get('entries')
    ) ?>

</p>

<?php endif; ?>


<?php if (empty($entries)): ?>

<p>
    <?= htmlspecialchars(
        $language->get('no_entries_found')
    ) ?>
</p>

<?php else: ?>

<table border="1" cellpadding="5" cellspacing="0">

    <thead>

        <tr>

            <th>
                <a href="/entry?search=<?= urlencode($search ?? '') ?>&sort=id">
                    ID
                </a>
            </th>


            <th>
                <a href="/entry?search=<?= urlencode($search ?? '') ?>&sort=title">
                    <?= htmlspecialchars(
                        $language->get('title')
                    ) ?>
                </a>
            </th>


            <th>
                <a href="/entry?search=<?= urlencode($search ?? '') ?>&sort=year">
                    <?= htmlspecialchars(
                        $language->get('year')
                    ) ?>
                </a>
            </th>


            <th>
                <a href="/entry?search=<?= urlencode($search ?? '') ?>&sort=releases">
                    <?= htmlspecialchars(
                        $language->get('release_count')
                    ) ?>
                </a>
            </th>


            <th>
                <a href="/entry?search=<?= urlencode($search ?? '') ?>&sort=tags">
                    <?= htmlspecialchars(
                        $language->get('tags')
                    ) ?>
                </a>
            </th>


            <th>
                <?= htmlspecialchars(
                    $language->get('action')
                ) ?>
            </th>

        </tr>

    </thead>


    <tbody>

    <?php foreach ($entries as $item): ?>

        <?php
        $entry = $item['entry'];
        $tags = $item['tags'];
        ?>

        <tr>

            <td>
                <?= htmlspecialchars(
                    (string) $entry->getId()
                ) ?>
            </td>


            <td>
                <a href="/entry?id=<?= $entry->getId() ?>">
                    <?= htmlspecialchars(
                        $entry->getTitle()
                    ) ?>
                </a>
            </td>


            <td>
                <?= $entry->getYear() !== null
                    ? htmlspecialchars((string) $entry->getYear())
                    : '' ?>
            </td>


            <td>
                <?= htmlspecialchars(
                    (string) $item['releaseCount']
                ) ?>
            </td>


            <td>
                <?= htmlspecialchars(
                    $tags
                ) ?>
            </td>


            <td>
                <a href="/entry?id=<?= $entry->getId() ?>">
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

        <a href="/entry?search=<?= urlencode($search ?? '') ?>&sort=<?= urlencode($sort ?? 'id') ?>&page=<?= $page - 1 ?>">
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

            <a href="/entry?search=<?= urlencode($search ?? '') ?>&sort=<?= urlencode($sort ?? 'id') ?>&page=<?= $i ?>">
                <?= $i ?>
            </a>

        <?php endif; ?>

    <?php endfor; ?>


    <?php if (($page ?? 1) < $pages): ?>

        <a href="/entry?search=<?= urlencode($search ?? '') ?>&sort=<?= urlencode($sort ?? 'id') ?>&page=<?= $page + 1 ?>">
            <?= htmlspecialchars(
                $language->get('next')
            ) ?>
        </a>

    <?php endif; ?>

</p>

<?php endif; ?>
