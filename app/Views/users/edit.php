<h2>
    <?= htmlspecialchars(
        $language->get('edit_user')
    ) ?>
</h2>


<form method="post" action="/users/edit">

    <input
        type="hidden"
        name="id"
        value="<?= htmlspecialchars(
            (string) $user->getId()
        ) ?>"
    >


    <table>

        <tr>

            <td>
                <?= htmlspecialchars(
                    $language->get('username')
                ) ?>
            </td>

            <td>

                <?= htmlspecialchars(
                    $user->getUsername()
                ) ?>

            </td>

        </tr>


        <tr>

            <td>
                <?= htmlspecialchars(
                    $language->get('email')
                ) ?>
            </td>

            <td>

                <?= htmlspecialchars(
                    $user->getEmail()
                ) ?>

            </td>

        </tr>


        <tr>

            <td>
                <?= htmlspecialchars(
                    $language->get('first_name')
                ) ?>
            </td>

            <td>

                <?= htmlspecialchars(
                    $user->getFirstName() ?? ''
                ) ?>

            </td>

        </tr>


        <tr>

            <td>
                <?= htmlspecialchars(
                    $language->get('last_name')
                ) ?>
            </td>

            <td>

                <?= htmlspecialchars(
                    $user->getLastName() ?? ''
                ) ?>

            </td>

        </tr>


        <tr>

            <td>
                <?= htmlspecialchars(
                    $language->get('role')
                ) ?>
            </td>

            <td>

                <select name="role_id">

                    <?php foreach ($roles as $role): ?>

                        <option
                            value="<?= $role->getId() ?>"
                            <?= $role->getId()
                                === $user->getRoleId()
                                ? 'selected'
                                : ''
                            ?>
                        >

                            <?= htmlspecialchars(
                                $role->getName()
                            ) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </td>

        </tr>


        <tr>

            <td></td>

            <td>

                <button type="submit">
                    <?= htmlspecialchars(
                        $language->get('save')
                    ) ?>
                </button>

                <a href="/users">
                    <?= htmlspecialchars(
                        $language->get('cancel')
                    ) ?>
                </a>

            </td>

        </tr>


    </table>

</form>
