<?php

declare(strict_types=1);

?>

<h2>
    <?= htmlspecialchars(
        $language->get('edit_comment')
    ) ?>
</h2>


<form
    method="post"
    action="/disk/comment/edit"
>

    <input
        type="hidden"
        name="id"
        value="<?= $release->getId() ?>"
    >


    <label for="notes">
        <?= htmlspecialchars(
            $language->get('comment')
        ) ?>
    </label>


    <br>


    <textarea
        id="notes"
        name="notes"
        rows="8"
        cols="80"
    ><?= htmlspecialchars(
        $release->getNotes() ?? ''
    ) ?></textarea>


    <br>
    <br>


    <button type="submit">
        <?= htmlspecialchars(
            $language->get('save')
        ) ?>
    </button>


    <a
        href="/disk?id=<?= $release->getId() ?>"
    >
        <?= htmlspecialchars(
            $language->get('cancel')
        ) ?>
    </a>

</form>
