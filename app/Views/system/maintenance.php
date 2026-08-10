<?php

declare(strict_types=1);
?>

<style>
    table {
        text-align: left;
    }

    th,
    td {
        text-align: left;
    }

    .maintenance-action {
        display: inline;
        margin: 0;
        padding: 0;
        border: 0;
        background: none;
        color: inherit;
        font: inherit;
        text-decoration: underline;
        cursor: pointer;
    }
</style>


<h1>
    <?= htmlspecialchars(
        $language->get('maintenance')
    ) ?>
</h1>


<h2>
    <?= htmlspecialchars(
        $language->get('import_storage')
    ) ?>
</h2>

<table>
    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('status')
            ) ?>
        </th>

        <td>
            <?php if ($storage['exists']): ?>

                <?php if (
                    $storage['readable'] &&
                    $storage['writable']
                ): ?>

                    <?= htmlspecialchars(
                        $language->get('ok')
                    ) ?>

                <?php else: ?>

                    <?= htmlspecialchars(
                        $language->get('warning')
                    ) ?>

                <?php endif; ?>

            <?php else: ?>

                <?= htmlspecialchars(
                    $language->get('error')
                ) ?>

            <?php endif; ?>
        </td>
    </tr>

    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('import_directory')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars(
                $storage['directory']
            ) ?>
        </td>
    </tr>

    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('physical_files')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars(
                (string) $storage['fileCount']
            ) ?>
        </td>
    </tr>

    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('used_space')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars(
                $storage['usedSpace']
            ) ?>
        </td>
    </tr>

    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('total_space')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars(
                $storage['totalSpace']
            ) ?>
        </td>
    </tr>

    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('free_space')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars(
                $storage['freeSpace']
            ) ?>
        </td>
    </tr>

    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('readable')
            ) ?>
        </th>

        <td>
            <?= $storage['readable']
                ? htmlspecialchars(
                    $language->get('yes')
                )
                : htmlspecialchars(
                    $language->get('no')
                )
            ?>
        </td>
    </tr>

    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('writable')
            ) ?>
        </th>

        <td>
            <?= $storage['writable']
                ? htmlspecialchars(
                    $language->get('yes')
                )
                : htmlspecialchars(
                    $language->get('no')
                )
            ?>
        </td>
    </tr>
</table>


<h2>
    <?= htmlspecialchars(
        $language->get('database')
    ) ?>
</h2>

<table>
    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('status')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars(
                $language->get('ok')
            ) ?>
        </td>
    </tr>

    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('registered_files')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars(
                (string) $database['fileCount']
            ) ?>
        </td>
    </tr>
</table>


<h2>
    <?= htmlspecialchars(
        $language->get('file_integrity')
    ) ?>
</h2>

<table>
    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('status')
            ) ?>
        </th>

        <td>
            <?php if (
                $fileIntegrity['missingCount'] === 0 &&
                $fileIntegrity['orphanCount'] === 0
            ): ?>

                <?= htmlspecialchars(
                    $language->get('ok')
                ) ?>

            <?php else: ?>

                <?= htmlspecialchars(
                    $language->get('warning')
                ) ?>

            <?php endif; ?>
        </td>
    </tr>

    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('registered_files')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars(
                (string) $fileIntegrity['registeredCount']
            ) ?>
        </td>
    </tr>

    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('physical_files')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars(
                (string) $fileIntegrity['physicalCount']
            ) ?>
        </td>
    </tr>

    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('missing_on_disk')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars(
                (string) $fileIntegrity['missingCount']
            ) ?>
        </td>
    </tr>

    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('unregistered_on_disk')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars(
                (string) $fileIntegrity['orphanCount']
            ) ?>
        </td>
    </tr>
</table>


<?php if ($fileIntegrity['missingCount'] > 0): ?>

    <h3>
        <?= htmlspecialchars(
            $language->get('missing_files')
        ) ?>
    </h3>

    <ul>
        <?php foreach (
            $fileIntegrity['missingFiles']
            as $filename
        ): ?>

            <li>
                <?= htmlspecialchars(
                    $filename
                ) ?>
            </li>

        <?php endforeach; ?>
    </ul>

<?php endif; ?>


<?php if ($fileIntegrity['orphanCount'] > 0): ?>

    <h3>
        <?= htmlspecialchars(
            $language->get('unregistered_files')
        ) ?>
    </h3>

    <table>
        <thead>
            <tr>
                <th>
                    <?= htmlspecialchars(
                        $language->get('file')
                    ) ?>
                </th>

                <th>
                    <?= htmlspecialchars(
                        $language->get('action')
                    ) ?>
                </th>
            </tr>
        </thead>

        <tbody>

            <?php foreach (
                $fileIntegrity['orphanFiles']
                as $filename
            ): ?>

                <tr>
                    <td>
                        <?= htmlspecialchars(
                            $filename
                        ) ?>
                    </td>

                    <td>
                        <a
                            href="/import?file=<?= urlencode(
                                $filename
                            ) ?>"
                        ><?= htmlspecialchars(
                            $language->get('import')
                        ) ?></a>

                        &nbsp;

                        <form
                            method="post"
                            action="/administration/system/maintenance/delete"
                            style="display: inline;"
                        >
                            <input
                                type="hidden"
                                name="filename"
                                value="<?= htmlspecialchars(
                                    $filename,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                            >

                            <input
                                type="submit"
                                value="<?= htmlspecialchars(
                                    $language->get('delete')
                                ) ?>"
                                class="maintenance-action"
                                onclick="return confirm(
                                    <?= json_encode(
                                        $language->get(
                                            'delete_file_confirm'
                                        )
                                    ) ?>
                                );"
                            >
                        </form>
                    </td>
                </tr>

            <?php endforeach; ?>

        </tbody>
    </table>

<?php endif; ?>


<h2>
    <?= htmlspecialchars(
        $language->get('database_file_check')
    ) ?>
</h2>

<table>
    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('status')
            ) ?>
        </th>

        <td>
            <?php if (
                $databaseFileIntegrity['missingPathCount'] === 0 &&
                $databaseFileIntegrity['sizeMismatchCount'] === 0
            ): ?>

                <?= htmlspecialchars(
                    $language->get('ok')
                ) ?>

            <?php else: ?>

                <?= htmlspecialchars(
                    $language->get('warning')
                ) ?>

            <?php endif; ?>
        </td>
    </tr>

    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('registered_files')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars(
                (string) $databaseFileIntegrity['registeredCount']
            ) ?>
        </td>
    </tr>

    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('valid_physical_path')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars(
                (string) $databaseFileIntegrity['validPathCount']
            ) ?>
        </td>
    </tr>

    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('missing_physical_file')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars(
                (string) $databaseFileIntegrity['missingPathCount']
            ) ?>
        </td>
    </tr>

    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('size_errors')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars(
                (string) $databaseFileIntegrity['sizeMismatchCount']
            ) ?>
        </td>
    </tr>
</table>


<?php if (
    $databaseFileIntegrity['missingPathCount'] > 0
): ?>

    <h3>
        <?= htmlspecialchars(
            $language->get('missing_physical_files')
        ) ?>
    </h3>

    <ul>
        <?php foreach (
            $databaseFileIntegrity['missingPaths']
            as $file
        ): ?>

            <li>
                <?= htmlspecialchars(
                    $file['filename']
                ) ?>

                —

                <?= htmlspecialchars(
                    $file['path']
                ) ?>
            </li>

        <?php endforeach; ?>
    </ul>

<?php endif; ?>


<?php if (
    $databaseFileIntegrity['sizeMismatchCount'] > 0
): ?>

    <h3>
        <?= htmlspecialchars(
            $language->get('size_errors')
        ) ?>
    </h3>

    <table>
        <thead>
            <tr>
                <th>ID</th>

                <th>
                    <?= htmlspecialchars(
                        $language->get('file')
                    ) ?>
                </th>

                <th>
                    <?= htmlspecialchars(
                        $language->get('database_size')
                    ) ?>
                </th>

                <th>
                    <?= htmlspecialchars(
                        $language->get('physical_size')
                    ) ?>
                </th>

                <th>
                    <?= htmlspecialchars(
                        $language->get('difference')
                    ) ?>
                </th>
            </tr>
        </thead>

        <tbody>

            <?php foreach (
                $databaseFileIntegrity['sizeMismatches']
                as $file
            ): ?>

                <?php
                $difference =
                    (int) $file['actualSize']
                    -
                    (int) $file['databaseSize'];
                ?>

                <tr>
                    <td>
                        <?= htmlspecialchars(
                            (string) $file['id']
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            $file['filename']
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            (string) $file['databaseSize']
                        ) ?>

                        <?= htmlspecialchars(
                            $language->get('bytes')
                        ) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars(
                            (string) $file['actualSize']
                        ) ?>

                        <?= htmlspecialchars(
                            $language->get('bytes')
                        ) ?>
                    </td>

                    <td>
                        <?= $difference >= 0
                            ? '+'
                            : ''
                        ?><?= htmlspecialchars(
                            (string) $difference
                        ) ?>

                        <?= htmlspecialchars(
                            $language->get('bytes')
                        ) ?>
                    </td>
                </tr>

            <?php endforeach; ?>

        </tbody>
    </table>

<?php endif; ?>
