<h1>
    Disk finns redan
</h1>


<p>
    Fil:
    <?= htmlspecialchars($filename) ?>
</p>


<p>
    MD5:
    <?= htmlspecialchars($md5) ?>
</p>


<p>
    Befintlig release:
    <?= $existing->getReleaseId() ?>
</p>


<form method="post"
      action="/import/force">


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


    <button type="submit">
        Importera som ny release
    </button>

</form>

