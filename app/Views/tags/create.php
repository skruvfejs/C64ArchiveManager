<?php

declare(strict_types=1);
?>

<h1>
    <?= htmlspecialchars(
        $language->get('new_tag')
    ) ?>
</h1>


<form method="post" action="/administration/tags/create">

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
        ></textarea>
    </p>


    <p>
        <button type="submit">
            <?= htmlspecialchars(
                $language->get('create_tag')
            ) ?>
        </button>

        <a href="/administration/tags">
            <?= htmlspecialchars(
                $language->get('cancel')
            ) ?>
        </a>
    </p>

</form>
