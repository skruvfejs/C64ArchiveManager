<?php

declare(strict_types=1);
?>

<h1>
    <?= htmlspecialchars(
        $language->get('tags')
    ) ?>
</h1>


<p>
    <?= htmlspecialchars(
        $language->get('tags_description')
    ) ?>
</p>


<p>
    <a href="/administration/tags/create">
        <?= htmlspecialchars(
            $language->get('new_tag')
        ) ?>
    </a>
</p>


<hr>


<?php if ($tags === []): ?>

<p>
    <?= htmlspecialchars(
        $language->get('no_tags_found')
    ) ?>
</p>

<?php else: ?>

<table>
    <thead>
        <tr>
            <th>
                <?= htmlspecialchars(
                    $language->get('tag_name')
                ) ?>
            </th>

            <th>
                <?= htmlspecialchars(
                    $language->get('tag_description')
                ) ?>
            </th>

            <th>
                <?= htmlspecialchars(
                    $language->get('actions')
                ) ?>
            </th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($tags as $tag): ?>

        <tr>
            <td>
                <?= htmlspecialchars(
                    $tag->getName()
                ) ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    $tag->getDescription()
                ) ?>
            </td>

            <td>
                <a href="/administration/tags/edit?id=<?= (int) $tag->getId() ?>">
                    <?= htmlspecialchars(
                        $language->get('edit')
                    ) ?>
                </a>

                <form
                    method="post"
                    action="/administration/tags/delete"
                    style="display: inline;"
                >
                    <input
                        type="hidden"
                        name="id"
                        value="<?= (int) $tag->getId() ?>"
                    >

                    <button
                        type="submit"
                        onclick="return confirm('<?= htmlspecialchars(
                            $language->get('confirm_delete_tag'),
                            ENT_QUOTES
                        ) ?>');"
                    >
                        <?= htmlspecialchars(
                            $language->get('delete_tag')
                        ) ?>
                    </button>
                </form>
            </td>
        </tr>

        <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>
