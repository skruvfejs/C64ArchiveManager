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

    h3 {
        margin-bottom: 5px;
    }
</style>


<p>
    <a href="/administration">
        <?= htmlspecialchars(
            $language->get('back_to_administration')
        ) ?>
    </a>
</p>

<h2>
    <?= htmlspecialchars(
        $language->get('system_information')
    ) ?>
</h2>


<h3>
    <?= htmlspecialchars(
        $language->get('application')
    ) ?>
</h3>

<table>
    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('name')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars($appName) ?>
        </td>
    </tr>

    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('version')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars($appVersion) ?>
        </td>
    </tr>
</table>


<h3>
    <?= htmlspecialchars(
        $language->get('environment')
    ) ?>
</h3>

<table>
    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('php_version')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars($phpVersion) ?>
        </td>
    </tr>

    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('operating_system')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars($operatingSystem) ?>
        </td>
    </tr>

    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('server_software')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars($serverSoftware) ?>
        </td>
    </tr>

    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('php_sapi')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars($phpSapi) ?>
        </td>
    </tr>
</table>


<h3>
    <?= htmlspecialchars(
        $language->get('database')
    ) ?>
</h3>

<table>
    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('database_name')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars($databaseName) ?>
        </td>
    </tr>

    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('database_version')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars($databaseVersion) ?>
        </td>
    </tr>

    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('tables')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars(
                (string) $tableCount
            ) ?>
        </td>
    </tr>

    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('database_size')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars($databaseSize) ?>
        </td>
    </tr>
</table>


<h3>
    <?= htmlspecialchars(
        $language->get('storage')
    ) ?>
</h3>

<table>
    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('imported_files')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars(
                (string) $importedFiles
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
            <?= htmlspecialchars($usedSpace) ?>
        </td>
    </tr>

    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('total_space')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars($totalSpace) ?>
        </td>
    </tr>

    <tr>
        <th>
            <?= htmlspecialchars(
                $language->get('free_space')
            ) ?>
        </th>

        <td>
            <?= htmlspecialchars($freeSpace) ?>
        </td>
    </tr>
</table>
