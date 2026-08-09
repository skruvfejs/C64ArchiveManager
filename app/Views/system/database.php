<?php

declare(strict_types=1);
?>

<h1>
    <?= htmlspecialchars(
        $language->get('database')
    ) ?>
</h1>


<p>
    <?= htmlspecialchars(
        $language->get('database_description')
    ) ?>
</p>


<hr>


<h2>
    <?= htmlspecialchars(
        $language->get('status')
    ) ?>
</h2>


<table style="width:100%;">

    <tr style="vertical-align:top;">

        <td style="width:50%;">

            <table>

                <tr>
                    <td>
                        <strong>
                            <?= htmlspecialchars(
                                $language->get('database')
                            ) ?>
                        </strong>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            (string) $database
                        ) ?>
                    </td>
                </tr>


                <tr>
                    <td>
                        <strong>
                            <?= htmlspecialchars(
                                $language->get('mariadb_version')
                            ) ?>
                        </strong>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            (string) $version
                        ) ?>
                    </td>
                </tr>


                <tr>
                    <td>
                        <strong>
                            <?= htmlspecialchars(
                                $language->get('table_count')
                            ) ?>
                        </strong>
                    </td>

                    <td>
                        <?= (int) $tables ?>
                    </td>
                </tr>


                <tr>
                    <td>
                        <strong>
                            <?= htmlspecialchars(
                                $language->get('database_size')
                            ) ?>
                        </strong>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            (string) $size
                        ) ?>
                    </td>
                </tr>

            </table>

        </td>


        <td style="width:50%;">

            <table>

                <tr>
                    <td>
                        <strong>
                            <?= htmlspecialchars(
                                $language->get('latest_backup')
                            ) ?>
                        </strong>
                    </td>

                    <td>

                        <?php if (!empty($lastBackup)): ?>

                            <?= htmlspecialchars(
                                $lastBackup['filename']
                            ) ?>

                        <?php else: ?>

                            <?= htmlspecialchars(
                                $language->get(
                                    'no_backup_found'
                                )
                            ) ?>

                        <?php endif; ?>

                    </td>
                </tr>


                <tr>
                    <td>
                        <strong>
                            <?= htmlspecialchars(
                                $language->get('backup_count')
                            ) ?>
                        </strong>
                    </td>

                    <td>
                        <?= (int) $backupCount ?>
                    </td>
                </tr>


                <tr>
                    <td>
                        <strong>
                            <?= htmlspecialchars(
                                $language->get('backup_storage')
                            ) ?>
                        </strong>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            (string) $backupSize
                        ) ?>
                    </td>
                </tr>

            </table>

        </td>

    </tr>

</table>


<hr>


<h2>
    <?= htmlspecialchars(
        $language->get('quick_actions')
    ) ?>
</h2>


<form
    method="post"
    action="/administration/system/backup/create"
>

    <button type="submit">
        <?= htmlspecialchars(
            $language->get('create_backup_now')
        ) ?>
    </button>

</form>


<hr>


<h2>
    <?= htmlspecialchars(
        $language->get('export')
    ) ?>
</h2>


<form
    method="post"
    action="/administration/system/export"
>

    <p>

        <label>

            <input
                type="radio"
                name="type"
                value="full"
                checked
            >

            <?= htmlspecialchars(
                $language->get('full_database')
            ) ?>

        </label>


        <br>


        <label>

            <input
                type="radio"
                name="type"
                value="archive"
            >

            <?= htmlspecialchars(
                $language->get('archive_data')
            ) ?>

        </label>


        <br>


        <label>

            <input
                type="radio"
                name="type"
                value="system"
            >

            <?= htmlspecialchars(
                $language->get('system_data')
            ) ?>

        </label>

    </p>


    <p>

        <label>
            <?= htmlspecialchars(
                $language->get('description')
            ) ?>
        </label>

        <br>


        <input
            type="text"
            name="description"
            size="60"
        >

    </p>


    <button type="submit">
        <?= htmlspecialchars(
            $language->get('export')
        ) ?>
    </button>

</form>


<hr>


<h2>
    <?= htmlspecialchars(
        $language->get('import')
    ) ?>
</h2>


<form
    method="post"
    action="/administration/system/import"
    enctype="multipart/form-data"
>

    <p>

        <label for="backup">
            <?= htmlspecialchars(
                $language->get('sql_file')
            ) ?>
        </label>

        <br><br>


        <button
            type="button"
            onclick="document.getElementById('backup').click();"
        >
            <?= htmlspecialchars(
                $language->get('choose_file')
            ) ?>
        </button>


        <span id="backup-file-name">
            <?= htmlspecialchars(
                $language->get('no_file_chosen')
            ) ?>
        </span>


        <input
            type="file"
            id="backup"
            name="backup"
            required
            style="display:none;"
        >

    </p>


    <button type="submit">
        <?= htmlspecialchars(
            $language->get('import')
        ) ?>
    </button>

</form>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('backup');
    const fileName = document.getElementById('backup-file-name');

    if (!input || !fileName) {
        return;
    }

    input.addEventListener('change', function () {

        if (input.files.length > 0) {
            fileName.textContent = input.files[0].name;
        } else {
            fileName.textContent =
                <?= json_encode(
                    $language->get('no_file_chosen')
                ) ?>;
        }

    });

});
</script>


<hr>


<h2>
    <?= htmlspecialchars(
        $language->get('backups')
    ) ?>
</h2>


<?php if (empty($backups)): ?>

    <p>
        <?= htmlspecialchars(
            $language->get('no_backups')
        ) ?>
    </p>

<?php else: ?>

    <table>

        <tr>

            <th style="text-align:left;">
                <?= htmlspecialchars(
                    $language->get('file')
                ) ?>
            </th>

            <th style="text-align:left;">
                <?= htmlspecialchars(
                    $language->get('type')
                ) ?>
            </th>

            <th style="text-align:left;">
                <?= htmlspecialchars(
                    $language->get('size')
                ) ?>
            </th>

            <th style="text-align:left;">
                <?= htmlspecialchars(
                    $language->get('date')
                ) ?>
            </th>

            <th style="text-align:left;">
                <?= htmlspecialchars(
                    $language->get('actions')
                ) ?>
            </th>

        </tr>


        <?php foreach ($backups as $backup): ?>

            <tr>

                <td>
                    <?= htmlspecialchars(
                        $backup['filename']
                    ) ?>
                </td>


                <td>
                    <?= htmlspecialchars(
                        $backup['type']
                    ) ?>
                </td>


                <td>
                    <?= htmlspecialchars(
                        $backup['sizeText']
                    ) ?>
                </td>


                <td>
                    <?= date(
                        'Y-m-d H:i:s',
                        $backup['modified']
                    ) ?>
                </td>


                <td>

                    <a
                        href="/administration/system/backup/download?file=<?= urlencode($backup['filename']) ?>"
                    >
                        <?= htmlspecialchars(
                            $language->get('download')
                        ) ?>
                    </a>

                    |


                    <form
                        method="post"
                        action="/administration/system/backup/restore"
                        style="display:inline;"
                        onsubmit="return confirm('<?= htmlspecialchars($language->get('restore_confirm'), ENT_QUOTES) ?>');"
                    >

                        <input
                            type="hidden"
                            name="file"
                            value="<?= htmlspecialchars($backup['filename']) ?>"
                        >


                        <button
                            type="submit"
                            style="
                                background:none;
                                border:none;
                                padding:0;
                                margin:0;
                                color:#06c;
                                text-decoration:underline;
                                cursor:pointer;
                                font:inherit;
                            "
                        >
                            <?= htmlspecialchars(
                                $language->get('restore')
                            ) ?>
                        </button>

                    </form>


                    |


                    <form
                        method="post"
                        action="/administration/system/backup/delete"
                        style="display:inline;"
                        onsubmit="return confirm('<?= htmlspecialchars($language->get('delete_backup_confirm'), ENT_QUOTES) ?>');"
                    >

                        <input
                            type="hidden"
                            name="file"
                            value="<?= htmlspecialchars($backup['filename']) ?>"
                        >


                        <button
                            type="submit"
                            style="
                                background:none;
                                border:none;
                                padding:0;
                                margin:0;
                                color:#06c;
                                text-decoration:underline;
                                cursor:pointer;
                                font:inherit;
                            "
                        >
                            <?= htmlspecialchars(
                                $language->get('delete')
                            ) ?>
                        </button>

                    </form>

                </td>

            </tr>

        <?php endforeach; ?>

    </table>

<?php endif; ?>
