<?php

declare(strict_types=1);
?>

<h1><?= htmlspecialchars($title ?? 'Logga in') ?></h1>

<?php if (!empty($error)): ?>
    <div style="
        color:#b00020;
        background:#ffeaea;
        border:1px solid #b00020;
        padding:10px;
        margin-bottom:20px;
    ">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<form method="post" action="/login">

    <div style="margin-bottom:15px;">
        <label for="username">Användarnamn</label><br>
        <input
            type="text"
            id="username"
            name="username"
            autocomplete="username"
            required
        >
    </div>

    <div style="margin-bottom:15px;">
        <label for="password">Lösenord</label><br>
        <input
            type="password"
            id="password"
            name="password"
            autocomplete="current-password"
            required
        >
    </div>

    <button type="submit">
        Logga in
    </button>

</form>

