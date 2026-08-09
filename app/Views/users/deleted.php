<h2>
    <?= htmlspecialchars(
        $language->get('deleted_users')
    ) ?>
</h2>


<?php if (empty($users)): ?>

    <p>
        <?= htmlspecialchars(
            $language->get('no_deleted_users_found')
        ) ?>
    </p>


<?php else: ?>


<table border="1" cellpadding="5" cellspacing="0">

    <thead>

        <tr>
            <th>ID</th>

            <th>
                <?= htmlspecialchars(
                    $language->get('username')
                ) ?>
            </th>

            <th>
                <?= htmlspecialchars(
                    $language->get('name')
                ) ?>
            </th>

            <th>
                <?= htmlspecialchars(
                    $language->get('email')
                ) ?>
            </th>

            <th>
                <?= htmlspecialchars(
                    $language->get('deleted')
                ) ?>
            </th>

            <th>
                <?= htmlspecialchars(
                    $language->get('action')
                ) ?>
            </th>
        </tr>

    </thead>


    <tbody>


    <?php foreach ($users as $user): ?>


        <tr>

            <td>
                <?= htmlspecialchars(
                    (string) $user->getId()
                ) ?>
            </td>


            <td>
                <?= htmlspecialchars(
                    $user->getUsername()
                ) ?>
            </td>


            <td>
                <?= htmlspecialchars(
                    trim(
                        ($user->getFirstName() ?? '')
                        . ' '
                        . ($user->getLastName() ?? '')
                    )
                ) ?>
            </td>


            <td>
                <?= htmlspecialchars(
                    $user->getEmail()
                ) ?>
            </td>


            <td>
                <?= htmlspecialchars(
                    (string) $user->getDeletedAt()
                ) ?>
            </td>


            <td>

                <form
                    method="post"
                    action="/users/restore"
                    style="display:inline;"
                >

                    <input
                        type="hidden"
                        name="id"
                        value="<?= $user->getId() ?>"
                    >


                    <button
                        type="submit"
                        style="border:0;background:none;padding:0;cursor:pointer;text-decoration:underline;font:inherit;"
                    >
                        <?= htmlspecialchars(
                            $language->get('restore')
                        ) ?>
                    </button>

                </form>

            </td>

        </tr>


    <?php endforeach; ?>


    </tbody>

</table>


<?php endif; ?>
