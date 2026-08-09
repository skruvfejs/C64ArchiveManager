<?php

declare(strict_types=1);

use App\Core\Auth;
use App\Core\Session;


$session = new Session();

$auth = new Auth($session);

$user = $auth->user();

?>


<h1>
    <?= htmlspecialchars($title) ?>
</h1>



<?php if ($user): ?>


    <p>
        <?= htmlspecialchars(
            $language->get('welcome_back')
        ) ?>
        <strong>
            <?= htmlspecialchars($user['username']) ?>
        </strong>
    </p>


    <p>
        <?= htmlspecialchars(
            $language->get('logged_in_archive_message')
        ) ?>
    </p>



    <h2>
        <?= htmlspecialchars(
            $language->get('latest_imports')
        ) ?>
    </h2>


    <?php if (!empty($imports)): ?>

        <table border="1" cellpadding="5" cellspacing="0">

            <thead>

                <tr>
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
                            $language->get('imported_by')
                        ) ?>
                    </th>

                    <th>
                        <?= htmlspecialchars(
                            $language->get('started')
                        ) ?>
                    </th>
                </tr>

            </thead>


            <tbody>

            <?php foreach ($imports as $import): ?>

                <tr>

                    <td>
                        <?= htmlspecialchars(
                            $import->getFilename()
                        ) ?>
                    </td>


                    <td>
                        <?= htmlspecialchars(
                            $import->getFormat()
                        ) ?>
                    </td>


                    <td>
                        <?= htmlspecialchars(
                            $import->getStatus()
                        ) ?>
                    </td>


                    <td>
                        <?= htmlspecialchars(
                            $import->getUsername() ?? ''
                        ) ?>
                    </td>


                    <td>
                        <?= htmlspecialchars(
                            $import->getStartedAt()
                        ) ?>
                    </td>

                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>


    <?php else: ?>

        <p>
            <?= htmlspecialchars(
                $language->get('no_imports_yet')
            ) ?>
        </p>

    <?php endif; ?>


<?php else: ?>


    <p>
        <?= htmlspecialchars(
            $language->get('welcome_to_archive')
        ) ?>
    </p>


    <p>

        <a href="/login">
            <?= htmlspecialchars(
                $language->get('login')
            ) ?>
        </a>

    </p>


    <p>

        <a href="/register">
            <?= htmlspecialchars(
                $language->get('register')
            ) ?>
        </a>

    </p>



<?php endif; ?>



<p>
    <?= htmlspecialchars(
        $language->get('version')
    ) ?>
    <?= htmlspecialchars($version) ?>
</p>
