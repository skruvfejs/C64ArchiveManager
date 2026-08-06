<h2>
    <?= htmlspecialchars($title ?? 'Skapa användare') ?>
</h2>


<form method="post" action="/users/create">


    <p>
        <label>
            Förnamn
        </label>
        <br>

        <input
            type="text"
            name="first_name"
        >
    </p>



    <p>
        <label>
            Efternamn
        </label>
        <br>

        <input
            type="text"
            name="last_name"
        >
    </p>



    <p>
        <label>
            Användarnamn
        </label>
        <br>

        <input
            type="text"
            name="username"
            required
        >
    </p>



    <p>
        <label>
            E-post
        </label>
        <br>

        <input
            type="email"
            name="email"
            required
        >
    </p>



    <p>
        <label>
            Lösenord
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
            Roll
        </label>
        <br>

        <select name="role_id">


            <?php foreach ($roles as $role): ?>

                <option
                    value="<?= $role->getId() ?>"
                    <?= $role->getId() === \App\Core\Role::READONLY ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars(
                        $role->getName()
                    ) ?>
                </option>


            <?php endforeach; ?>


        </select>
    </p>



    <p>

        <button type="submit">
            Skapa användare
        </button>

    </p>


</form>


<p>

    <a href="/users">
        Tillbaka till användare
    </a>

</p>
