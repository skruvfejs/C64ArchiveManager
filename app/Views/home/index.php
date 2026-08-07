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
