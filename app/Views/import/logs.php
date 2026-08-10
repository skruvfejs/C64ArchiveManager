<h1>
    <?= htmlspecialchars(
        $language->get('import_logs')
    ) ?>
</h1>


<?php if (empty($logs)): ?>

<p>
    <?= htmlspecialchars(
        $language->get('no_imports_found')
    ) ?>
</p>


<?php else: ?>


<table border="1">

    <tr>

        <th>
            <?= htmlspecialchars(
                $language->get('user')
            ) ?>
        </th>

        <th>
            <?= htmlspecialchars(
                $language->get('date')
            ) ?>
        </th>

        <th>
            <?= htmlspecialchars(
                $language->get('file')
            ) ?>
        </th>

        <th>
            <?= htmlspecialchars(
                $language->get('format')
            ) ?>
        </th>

        <th>
            <?= htmlspecialchars(
                $language->get('status')
            ) ?>
        </th>

        <th>
            <?= htmlspecialchars(
                $language->get('release')
            ) ?>
        </th>

        <th>
            <?= htmlspecialchars(
                $language->get('files_imported')
            ) ?>
        </th>

        <th>
            <?= htmlspecialchars(
                $language->get('message')
            ) ?>
        </th>

    </tr>


    <?php foreach ($logs as $log): ?>

    <tr>

        <td>
            <?= htmlspecialchars(
                $log->getUsername() ?? $language->get('guest')
            ) ?>
        </td>


        <td>
            <?= htmlspecialchars(
                $log->getStartedAt() !== null
                ? $dateService->format($log->getStartedAt())
                : '-'
            ) ?>
        </td>


        <td>
            <?= htmlspecialchars(
                $log->getFilename()
            ) ?>
        </td>


        <td>
            <?= htmlspecialchars(
                $log->getFormat()
            ) ?>
        </td>


        <td>
            <?= htmlspecialchars(
                $log->getStatus()
            ) ?>
        </td>


        <td>
            <?= $log->getReleaseId() ?? '-' ?>
        </td>


        <td>
            <?= $log->getFilesImported() ?>
        </td>


        <td>
            <?= htmlspecialchars(
                $log->getMessage() ?? ''
            ) ?>
        </td>

    </tr>


    <?php endforeach; ?>


</table>


<?php endif; ?>
