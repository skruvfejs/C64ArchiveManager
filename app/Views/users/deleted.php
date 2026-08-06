<h2><?= htmlspecialchars($title ?? 'Borttagna användare') ?></h2>


<?php if (empty($users)): ?>

    <p>
        Inga borttagna användare hittades.
    </p>


<?php else: ?>


<table border="1" cellpadding="5" cellspacing="0">

    <thead>

        <tr>
            <th>ID</th>
            <th>Användarnamn</th>
            <th>Namn</th>
            <th>E-post</th>
            <th>Borttagen</th>
            <th>Åtgärd</th>
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
                        Återställ
                    </button>

                </form>

            </td>

        </tr>


    <?php endforeach; ?>


    </tbody>

</table>


<?php endif; ?>
