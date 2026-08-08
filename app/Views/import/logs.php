<h1>
    Import Logs
</h1>


<?php if (empty($logs)): ?>

<p>
    Inga importer hittades.
</p>


<?php else: ?>


<table border="1">

    <tr>

        <th>
            Användare
        </th>

        <th>
            Datum
        </th>

        <th>
            Fil
        </th>

        <th>
            Format
        </th>

        <th>
            Status
        </th>

        <th>
            Release
        </th>

        <th>
            Antal filer
        </th>

        <th>
            Meddelande
        </th>

    </tr>



    <?php foreach ($logs as $log): ?>

    <tr>

        <td>
            <?= htmlspecialchars(
                $log->getUsername() ?? 'Gäst'
            ) ?>
        </td>


        <td>
            <?= htmlspecialchars(
                $log->getStartedAt() ?? '-'
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
