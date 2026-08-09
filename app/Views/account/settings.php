<h2>
    <?= htmlspecialchars($title ?? 'Användarinställningar') ?>
</h2>


<h3>
    Språk
</h3>


<form method="post" action="/account/settings">

    <p>
        <label>
            Språk
        </label>
        <br>

        <select name="language" required>
            <option
                value="sv"
                <?= ($user->getLanguage() === 'sv') ? 'selected' : '' ?>
            >
                Svenska
            </option>

            <option
                value="en"
                <?= ($user->getLanguage() === 'en') ? 'selected' : '' ?>
            >
                English
            </option>
        </select>
    </p>


    <hr>


    <h3>
        Appearance
    </h3>


    <p>
        <label>
            <input
                type="radio"
                name="theme"
                value="system"
                <?= ($user->getTheme() === 'system') ? 'checked' : '' ?>
            >
            System
        </label>
        <br>

        <label>
            <input
                type="radio"
                name="theme"
                value="light"
                <?= ($user->getTheme() === 'light') ? 'checked' : '' ?>
            >
            Light
        </label>
        <br>

        <label>
            <input
                type="radio"
                name="theme"
                value="dark"
                <?= ($user->getTheme() === 'dark') ? 'checked' : '' ?>
            >
            Dark
        </label>
    </p>


    <p>

        <button type="submit">
            Spara
        </button>

    </p>

</form>


<hr>


<h3>
    Lösenord
</h3>


<p>

    <a href="/account/password">
        Ändra lösenord
    </a>

</p>
