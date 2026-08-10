<?php

declare(strict_types=1);

?>

<div class="container">

    <h1>
        <?= htmlspecialchars(
            $language->get('audit_log')
        ) ?>
    </h1>


    <table class="table" style="text-align: left;">

        <thead>

            <tr>

                <th style="text-align: left;">
                    <?= htmlspecialchars(
                        $language->get('date')
                    ) ?>
                </th>

                <th style="text-align: left;">
                    <?= htmlspecialchars(
                        $language->get('user')
                    ) ?>
                </th>

                <th style="text-align: left;">
                    <?= htmlspecialchars(
                        $language->get('event')
                    ) ?>
                </th>

                <th style="text-align: left;">
                    <?= htmlspecialchars(
                        $language->get('type')
                    ) ?>
                </th>

                <th style="text-align: left;">
                    ID
                </th>

                <th style="text-align: left;">
                    <?= htmlspecialchars(
                        $language->get('description')
                    ) ?>
                </th>

            </tr>

        </thead>


        <tbody>

        <?php foreach ($logs as $log): ?>

            <tr>

                <td style="text-align: left;">
                    <?= htmlspecialchars(
                        $dateService->format($log['created_at'])
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

                        <?= htmlspecialchars(
                            $language->get('guest')
                        ) ?>

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
