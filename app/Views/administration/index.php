<?php

declare(strict_types=1);
?>

<h1>
    <?= htmlspecialchars(
        $language->get('administration')
    ) ?>
</h1>


<p>
    <?= htmlspecialchars(
        $language->get('administration_description')
    ) ?>
</p>


<hr>


<h2>
    <?= htmlspecialchars(
        $language->get('users')
    ) ?>
</h2>


<p>
    <?= htmlspecialchars(
        $language->get('users_description')
    ) ?>
</p>


<p>
    <a href="/users">
        <?= htmlspecialchars(
            $language->get('open_user_administration')
        ) ?>
    </a>
</p>


<hr>


<h2>
    <?= htmlspecialchars(
        $language->get('system_administration')
    ) ?>
</h2>


<p>
    <?= htmlspecialchars(
        $language->get('system_administration_description')
    ) ?>
</p>


<p>
    <a href="/administration/system">
        <?= htmlspecialchars(
            $language->get('open_system_administration')
        ) ?>
    </a>
</p>
