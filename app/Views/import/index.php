<h1>
    C64 Import
</h1>


<?php if (!empty($message)): ?>

<p>
    <?= $message ?>
</p>

<?php endif; ?>


<form method="post"
      action="/import"
      enctype="multipart/form-data">


    <p>
        Entry:
    </p>


    <select name="entry_id">

        <option value="">
            -- Skapa automatiskt --
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
        Disk image:
    </p>


    <input
        type="file"
        name="disk"
    >



    <p>

        <button type="submit">
            Import
        </button>

    </p>


</form>

