<h2><?= htmlspecialchars($title ?? 'Användare') ?></h2>


<p>

    <a href="/users/create">
        Ny användare
    </a>

    |

    <a href="/users/deleted">
        Borttagna användare
    </a>

    |

    <a href="/users/logs">
        Audit log
    </a>

</p>



<?php if (empty($users)): ?>

    <p>
        Inga användare hittades.
    </p>


<?php else: ?>


<table border="1" cellpadding="5" cellspacing="0">

    <thead>

        <tr>
            <th>ID</th>
            <th>Användarnamn</th>
            <th>Namn</th>
            <th>E-post</th>
            <th>Roll</th>
            <th>Skapad</th>
            <th>Åtgärd</th>
        </tr>

    </thead>


    <tbody>


    <?php foreach ($users as $user): ?>

        <?php

        $roleName = 'Okänd';


        foreach ($roles as $role) {

            if (
                $role->getId()
                === $user->getRoleId()
            ) {

                $roleName =
                    $role->getName();

                break;
            }
        }


        $isSuperAdmin =
            $user->getRoleId()
            === \App\Core\Role::SUPER_ADMIN;


        $isOwnAccount =
            isset($_SESSION['user'])
            &&
            $_SESSION['user']['id']
            === $user->getId();

        ?>


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
                    $roleName
                ) ?>
            </td>


            <td>
                <?= htmlspecialchars(
                    (string) $user->getCreatedAt()
                ) ?>
            </td>


            <td>

                <a href="/users/edit?id=<?= $user->getId() ?>">
                    Redigera
                </a>


                <?php if (
                    !$isOwnAccount
                    &&
                    !$isSuperAdmin
                ): ?>

                    &nbsp;

                    <form
                        method="post"
                        action="/users/delete"
                        style="display:inline;"
                        onsubmit="return confirm('Är du säker på att du vill ta bort användaren?');"
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
                            Ta bort
                        </button>

                    </form>

                <?php endif; ?>


            </td>

        </tr>


    <?php endforeach; ?>


    </tbody>

</table>


<?php endif; ?>
