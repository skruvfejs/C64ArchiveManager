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
        Välkommen tillbaka,
        <strong>
            <?= htmlspecialchars($user['username']) ?>
        </strong>
    </p>


    <p>
        Du är inloggad och kan använda arkivet.
    </p>



    <h2>
        Senast importerade releaser
    </h2>


    <?php if (!empty($imports)): ?>

        <table border="1" cellpadding="5" cellspacing="0">

            <thead>

                <tr>
                    <th>Fil</th>
                    <th>Format</th>
                    <th>Status</th>
                    <th>Importerad av</th>
                    <th>Startad</th>
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
            Inga importer ännu.
        </p>

    <?php endif; ?>


<?php else: ?>


    <p>
        Välkommen till C64 Archive Manager.
    </p>


    <p>

        <a href="/login">
            Logga in
        </a>

    </p>


    <p>

        <a href="/register">
            Registrera användare
        </a>

    </p>



<?php endif; ?>



<p>
    Version <?= htmlspecialchars($version) ?>
</p>
