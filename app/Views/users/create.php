<p>
    <a href="/users">
        <?= htmlspecialchars(
            $language->get('back_to_users')
        ) ?>
    </a>
</p>

<h2>
    <?= htmlspecialchars(
        $language->get('create_user')
    ) ?>
</h2>


<form method="post" action="/users/create">


    <p>
        <label>
            <?= htmlspecialchars(
                $language->get('first_name')
            ) ?>
        </label>
        <br>

        <input
            type="text"
            name="first_name"
        >
    </p>



    <p>
        <label>
            <?= htmlspecialchars(
                $language->get('last_name')
            ) ?>
        </label>
        <br>

        <input
            type="text"
            name="last_name"
        >
    </p>



    <p>
        <label>
            <?= htmlspecialchars(
                $language->get('username')
            ) ?>
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
            <?= htmlspecialchars(
                $language->get('email')
            ) ?>
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
            <?= htmlspecialchars(
                $language->get('password')
            ) ?>
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
            <?= htmlspecialchars(
                $language->get('role')
            ) ?>
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
            <?= htmlspecialchars(
                $language->get('create_user')
            ) ?>
        </button>

    </p>


</form>


