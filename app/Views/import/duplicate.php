<h1>
    <?= htmlspecialchars(
        $language->get('disk_already_exists')
    ) ?>
</h1>


<p>
    <?= htmlspecialchars(
        $language->get('file')
    ) ?>:
    <?= htmlspecialchars($filename) ?>
</p>


<p>
    MD5:
    <?= htmlspecialchars($md5) ?>
</p>


<p>
    <?= htmlspecialchars(
        $language->get('existing_release')
    ) ?>:
    <?= $existing->getReleaseId() ?>
</p>


<form
    method="post"
    action="/import/force"
>

    <input
        type="hidden"
        name="entry_id"
        value="<?= $entryId ?>"
    >


    <input
        type="hidden"
        name="path"
        value="<?= htmlspecialchars($path) ?>"
    >


    <input
        type="hidden"
        name="notes"
        value="<?= htmlspecialchars($notes ?? '') ?>"
    >


    <button type="submit">
        <?= htmlspecialchars(
            $language->get('import_as_new_release')
        ) ?>
    </button>

</form>
