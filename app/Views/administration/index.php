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


<?php if (
    $authorization->can(
        \App\Core\Permission::MANAGE_TAGS
    )
): ?>

<h2>
    <?= htmlspecialchars(
        $language->get('tags')
    ) ?>
</h2>


<p>
    <?= htmlspecialchars(
        $language->get('tags_description')
    ) ?>
</p>


<p>
    <a href="/administration/tags">
        <?= htmlspecialchars(
            $language->get('tags')
        ) ?>
    </a>
</p>


<?php endif; ?>


<hr>


<?php if (
    $authorization->can(
        \App\Core\Permission::MANAGE_SYSTEM
    )
): ?>

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
    <a href="/administration/system/database">
        <?= htmlspecialchars(
            $language->get('database')
        ) ?>
    </a>
</p>


<p>
    <a href="/administration/system/settings">
        <?= htmlspecialchars(
            $language->get('system_settings')
        ) ?>
    </a>
</p>


<p>
    <a href="/administration/system/maintenance">
        <?= htmlspecialchars(
            $language->get('maintenance')
        ) ?>
    </a>
</p>


<p>
    <a href="/administration/system/information">
        <?= htmlspecialchars(
            $language->get('information')
        ) ?>
    </a>
</p>

<?php endif; ?>
