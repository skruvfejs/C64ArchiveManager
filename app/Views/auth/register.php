<h2>Registrera användare</h2>

<?php if (!empty($success)): ?>

    <p style="color: green;">
        <?= htmlspecialchars($success) ?>
    </p>

<?php endif; ?>

<?php if (!empty($error)): ?>

    <p style="color: red;">
        <?= htmlspecialchars($error) ?>
    </p>

<?php endif; ?>

<form method="post" action="/register">

    <table>

        <tr>
            <td>Förnamn</td>
            <td>
                <input
                    type="text"
                    name="first_name"
                    maxlength="100"
                >
            </td>
        </tr>

        <tr>
            <td>Efternamn</td>
            <td>
                <input
                    type="text"
                    name="last_name"
                    maxlength="100"
                >
            </td>
        </tr>

        <tr>
            <td>Användarnamn</td>
            <td>
                <input
                    type="text"
                    name="username"
                    maxlength="50"
                    required
                >
            </td>
        </tr>

        <tr>
            <td>E-post</td>
            <td>
                <input
                    type="email"
                    name="email"
                    maxlength="255"
                    required
                >
            </td>
        </tr>

        <tr>
            <td>Lösenord</td>
            <td>
                <input
                    type="password"
                    name="password"
                    required
                >
            </td>
        </tr>

        <tr>
            <td></td>
            <td>

                <button type="submit">

                    Registrera

                </button>

            </td>
        </tr>

    </table>

</form>
