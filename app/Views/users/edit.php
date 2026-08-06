<h2><?= htmlspecialchars($title ?? 'Redigera användare') ?></h2>

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
                Användarnamn
            </td>

            <td>

                <?= htmlspecialchars(
                    $user->getUsername()
                ) ?>

            </td>

        </tr>


        <tr>

            <td>
                E-post
            </td>

            <td>

                <?= htmlspecialchars(
                    $user->getEmail()
                ) ?>

            </td>

        </tr>


        <tr>

            <td>
                Förnamn
            </td>

            <td>

                <?= htmlspecialchars(
                    $user->getFirstName() ?? ''
                ) ?>

            </td>

        </tr>


        <tr>

            <td>
                Efternamn
            </td>

            <td>

                <?= htmlspecialchars(
                    $user->getLastName() ?? ''
                ) ?>

            </td>

        </tr>


        <tr>

            <td>
                Roll
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
                    Spara
                </button>

                <a href="/users">
                    Avbryt
                </a>

            </td>

        </tr>


    </table>

</form>
