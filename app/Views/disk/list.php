<?php

declare(strict_types=1);

?>


<h2>
    <?= htmlspecialchars($title ?? 'Diskar') ?>
</h2>


<p>

    <a href="/import">
        Importera ny release
    </a>

    |

    <a href="/import/logs">
        Importlogg
    </a>

</p>


<?php if (empty($disks)): ?>

    <p>
        Inga diskar hittades.
    </p>


<?php else: ?>


<table border="1" cellpadding="5" cellspacing="0">

    <thead>

        <tr>
            <th>
                ID
            </th>

            <th>
                Disknamn
            </th>

            <th>
                Filnamn
            </th>

            <th>
                Format
            </th>

            <th>
                Storlek
            </th>

            <th>
                MD5
            </th>

            <th>
                Åtgärd
            </th>
        </tr>

    </thead>


    <tbody>


    <?php foreach ($disks as $disk): ?>


        <tr>

            <td>
                <?= htmlspecialchars(
                    (string) $disk->getId()
                ) ?>
            </td>


            <td>
                <?= htmlspecialchars(
                    $disk->getDiskName() ?? ''
                ) ?>
            </td>


            <td>
                <?= htmlspecialchars(
                    $disk->getFilename()
                ) ?>
            </td>


            <td>
                <?= htmlspecialchars(
                    $disk->getFormat()
                ) ?>
            </td>


            <td>
                <?= htmlspecialchars(
                    (string) $disk->getSize()
                ) ?>
                bytes
            </td>


            <td>
                <?= htmlspecialchars(
                    $disk->getMd5()
                ) ?>
            </td>


            <td>

                <a href="/disk?id=<?= $disk->getId() ?>">
                    Visa
                </a>

            </td>


        </tr>


    <?php endforeach; ?>


    </tbody>

</table>


<?php endif; ?>
