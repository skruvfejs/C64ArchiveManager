<?php

declare(strict_types=1);

?>


<h1 style="color: red;">
    <?= htmlspecialchars(
        $language->get('maintenance_title')
    ) ?>
</h1>


<p style="color: red; font-weight: bold;">
    <?= htmlspecialchars(
        $language->get('maintenance_message')
    ) ?>
</p>


<p>
    <a href="/login">
        <?= htmlspecialchars(
            $language->get('login')
        ) ?>
    </a>
</p>
