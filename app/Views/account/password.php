<h2>
    <?= htmlspecialchars($title ?? 'Ändra lösenord') ?>
</h2>


<form method="post" action="/account/password">


    <p>
        <label>
            Nuvarande lösenord
        </label>
        <br>

        <input
            type="password"
            name="current_password"
            required
        >
    </p>



    <p>
        <label>
            Nytt lösenord
        </label>
        <br>

        <input
            type="password"
            name="password"
            required
        >
    </p>



    <p>
        <label>
            Bekräfta nytt lösenord
        </label>
        <br>

        <input
            type="password"
            name="password_confirmation"
            required
        >
    </p>



    <p>

        <button type="submit">
            Ändra lösenord
        </button>

    </p>


</form>


<p>

    <a href="/">
        Tillbaka
    </a>

</p>
