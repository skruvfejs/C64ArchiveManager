<?php

declare(strict_types=1);

?>


<h1>
    <?= htmlspecialchars(
        $language->get('c64_import')
    ) ?>
</h1>


<?php if (!empty($message)): ?>

<p>
    <?= $message ?>
</p>

<?php endif; ?>


<form
    method="post"
    action="/import"
    enctype="multipart/form-data"
>


    <p>
        <?= htmlspecialchars(
            $language->get('entry')
        ) ?>:
    </p>


    <select name="entry_id">

        <option value="">
            <?= htmlspecialchars(
                $language->get('create_automatically')
            ) ?>
        </option>


        <?php foreach ($entries as $entry): ?>

            <option value="<?= $entry->getId() ?>">

                <?= htmlspecialchars(
                    $entry->getTitle()
                ) ?>

            </option>

        <?php endforeach; ?>

    </select>


    <p>
        <?= htmlspecialchars(
            $language->get('comment_optional')
        ) ?>:
    </p>


    <textarea
        name="notes"
        rows="4"
        cols="60"
        placeholder="<?= htmlspecialchars(
            $language->get('comment_placeholder')
        ) ?>"
    ></textarea>


    <p>
        <?= htmlspecialchars(
            $language->get('disk_image')
        ) ?>:
    </p>


    <?php if (
        !empty($existingFile) &&
        !empty($existingFile['filename']) &&
        !empty($existingFile['path'])
    ): ?>

        <p>
            <strong>
                <?= htmlspecialchars(
                    $existingFile['filename']
                ) ?>
            </strong>
        </p>

        <input
            type="hidden"
            name="existing_path"
            value="<?= htmlspecialchars(
                $existingFile['path'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

    <?php else: ?>

        <button
            type="button"
            onclick="document.getElementById('disk').click();"
        >
            <?= htmlspecialchars(
                $language->get('choose_file')
            ) ?>
        </button>

        <span id="disk-file-name">
            <?= htmlspecialchars(
                $language->get('no_file_chosen')
            ) ?>
        </span>


        <input
            type="file"
            id="disk"
            name="disk"
            style="display:none;"
        >

    <?php endif; ?>


    <p>

        <button type="submit">

            <?= htmlspecialchars(
                $language->get('import')
            ) ?>

        </button>

    </p>


</form>


<?php if (
    empty($existingFile)
): ?>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const input =
        document.getElementById('disk');

    const fileName =
        document.getElementById('disk-file-name');


    if (!input || !fileName) {
        return;
    }


    input.addEventListener('change', function () {

        if (input.files.length > 0) {

            fileName.textContent =
                input.files[0].name;

        } else {

            fileName.textContent =
                <?= json_encode(
                    $language->get('no_file_chosen')
                ) ?>;
        }

    });

});
</script>

<?php endif; ?>
