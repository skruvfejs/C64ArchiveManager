<h2>
    <?= htmlspecialchars(
        $language->get('users')
    ) ?>
</h2>


<p>

    <a href="/users/create">
        <?= htmlspecialchars(
            $language->get('new_user')
        ) ?>
    </a>

    |

    <a href="/users/deleted">
        <?= htmlspecialchars(
            $language->get('deleted_users')
        ) ?>
    </a>

    |

    <a href="/users/logs">
        <?= htmlspecialchars(
            $language->get('audit_log')
        ) ?>
    </a>

</p>



<?php if (empty($users)): ?>

    <p>
        <?= htmlspecialchars(
            $language->get('no_users_found')
        ) ?>
    </p>


<?php else: ?>


<?php if (($total ?? 0) > 0): ?>

<p>

    <?= htmlspecialchars(
        $language->get('showing')
    ) ?>

    <?= (($page ?? 1) - 1) * ($perPage ?? 25) + 1 ?>

    -

    <?= min(
        ($page ?? 1) * ($perPage ?? 25),
        $total
    ) ?>

    <?= htmlspecialchars(
        $language->get('of')
    ) ?>

    <?= $total ?>

    <?= htmlspecialchars(
        $language->get('users')
    ) ?>

</p>

<?php endif; ?>


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
                    $language->get('role')
                ) ?>
            </th>

            <th>
                <?= htmlspecialchars(
                    $language->get('created')
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

        <?php

        $roleName = $language->get('unknown');

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
                    (string) $dateService->format($user->getCreatedAt())
                ) ?>
            </td>


            <td>

                <a href="/users/edit?id=<?= $user->getId() ?>">
                    <?= htmlspecialchars(
                        $language->get('edit')
                    ) ?>
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
                        onsubmit="return confirm(
                            <?= htmlspecialchars(
                                json_encode(
                                    $language->get(
                                        'confirm_delete_user'
                                    )
                                )
                            ) ?>
                        );"
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
                                $language->get('delete')
                            ) ?>
                        </button>

                    </form>

                <?php endif; ?>


            </td>

        </tr>


    <?php endforeach; ?>


    </tbody>

</table>


<?php endif; ?>


<?php if (($pages ?? 1) > 1): ?>

<p>


<?php if (($page ?? 1) > 1): ?>

<a href="/users?page=<?= $page - 1 ?>">

    <?= htmlspecialchars(
        $language->get('previous')
    ) ?>

</a>

<?php endif; ?>


<?php for ($i = 1; $i <= $pages; $i++): ?>

<?php if ($i === ($page ?? 1)): ?>

<strong>

    <?= $i ?>

</strong>

<?php else: ?>

<a href="/users?page=<?= $i ?>">

    <?= $i ?>

</a>

<?php endif; ?>


<?php endfor; ?>


<?php if (($page ?? 1) < $pages): ?>

<a href="/users?page=<?= $page + 1 ?>">

    <?= htmlspecialchars(
        $language->get('next')
    ) ?>

</a>

<?php endif; ?>


</p>

<?php endif; ?>
