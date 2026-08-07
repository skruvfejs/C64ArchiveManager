<?php

declare(strict_types=1);

?>

<div class="container">

    <h1>
        Audit log
    </h1>


    <table class="table" style="text-align: left;">

        <thead>

            <tr>

                <th style="text-align: left;">
                    Datum
                </th>

                <th style="text-align: left;">
                    Användare
                </th>

                <th style="text-align: left;">
                    Händelse
                </th>

                <th style="text-align: left;">
                    Typ
                </th>

                <th style="text-align: left;">
                    ID
                </th>

                <th style="text-align: left;">
                    Beskrivning
                </th>

            </tr>

        </thead>


        <tbody>

        <?php foreach ($logs as $log): ?>

            <tr>

                <td style="text-align: left;">
                    <?= htmlspecialchars(
                        $log['created_at']
                    ) ?>
                </td>


                <td style="text-align: left;">

                    <?php if (
                        $log['username'] !== null
                    ): ?>

                        <?= htmlspecialchars(
                            $log['username']
                        ) ?>

                    <?php else: ?>

                        Gäst

                    <?php endif; ?>

                </td>


                <td style="text-align: left;">
                    <?= htmlspecialchars(
                        $log['action']
                    ) ?>
                </td>


                <td style="text-align: left;">
                    <?= htmlspecialchars(
                        $log['target_type']
                    ) ?>
                </td>


                <td style="text-align: left;">
                    <?= htmlspecialchars(
                        (string) $log['target_id']
                    ) ?>
                </td>


                <td style="text-align: left;">
                    <?= htmlspecialchars(
                        $log['description']
                    ) ?>
                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

</div>
