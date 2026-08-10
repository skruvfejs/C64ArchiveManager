<?php

declare(strict_types=1);
?>

<h1>
    <?= htmlspecialchars(
        $language->get('edit_tag')
    ) ?>
</h1>


<form method="post" action="/administration/tags/edit">

    <input
        type="hidden"
        name="id"
        value="<?= (int) $tag->getId() ?>"
    >


    <p>
        <label for="name">
            <?= htmlspecialchars(
                $language->get('tag_name')
            ) ?>
        </label>
        <br>

        <input
            type="text"
            id="name"
            name="name"
            maxlength="100"
            value="<?= htmlspecialchars(
                $tag->getName()
            ) ?>"
            required
        >
    </p>


    <p>
        <label for="description">
            <?= htmlspecialchars(
                $language->get('tag_description')
            ) ?>
        </label>
        <br>

        <textarea
            id="description"
            name="description"
            rows="5"
        ><?= htmlspecialchars(
            $tag->getDescription() ?? ''
        ) ?></textarea>
    </p>


    <p>
        <button type="submit">
            <?= htmlspecialchars(
                $language->get('save')
            ) ?>
        </button>

        <a href="/administration/tags">
            <?= htmlspecialchars(
                $language->get('cancel')
            ) ?>
        </a>
    </p>

</form>
