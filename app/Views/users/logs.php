<?php

declare(strict_types=1);

?>

<div class="container">

    <h1>
        <?= htmlspecialchars(
            $language->get('audit_log')
        ) ?>
    </h1>


    <?php if (($total ?? 0) > 0): ?>

<p>

    <?= htmlspecialchars(
        $language->get('showing')
    ) ?>

    <?= (($page ?? 1) - 1) * ($perPage ?? 25) + 1 ?>

    -

    <?= min(
        ($page ?? 1) * ($perPage ?? 25),
        $total
    ) ?>

    <?= htmlspecialchars(
        $language->get('of')
    ) ?>

    <?= $total ?>

    <?= htmlspecialchars(
        $language->get('logs')
    ) ?>

</p>

<?php endif; ?>


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


<?php if (($pages ?? 1) > 1): ?>

<p>


<?php if (($page ?? 1) > 1): ?>

<a href="/users/logs?page=<?= $page - 1 ?>">

    <?= htmlspecialchars(
        $language->get('previous')
    ) ?>

</a>

<?php endif; ?>


<?php for ($i = 1; $i <= $pages; $i++): ?>

<?php if ($i === ($page ?? 1)): ?>

<strong>

    <?= $i ?>

</strong>

<?php else: ?>

<a href="/users/logs?page=<?= $i ?>">

    <?= $i ?>

</a>

<?php endif; ?>


<?php endfor; ?>


<?php if (($page ?? 1) < $pages): ?>

<a href="/users/logs?page=<?= $page + 1 ?>">

    <?= htmlspecialchars(
        $language->get('next')
    ) ?>

</a>

<?php endif; ?>


</p>

<?php endif; ?>
