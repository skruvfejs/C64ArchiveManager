<?php

declare(strict_types=1);

use App\Core\Auth;
use App\Core\Session;

$auth = new Auth(new Session());
$user = $auth->user();

$title ??= 'C64 Archive Manager';

?>
<!DOCTYPE html>
<html lang="sv">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($title) ?></title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #ececec;
            color: #222;
        }

        header {
            background: #2b2b2b;
            color: #fff;
            padding: 14px 24px;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        nav {
            margin-top: 12px;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            margin-right: 20px;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .user {
            float: right;
            font-size: 14px;
        }

        .user a {
            color: #fff;
            text-decoration: none;
        }

        .user a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 1200px;
            margin: 25px auto;
            padding: 25px;
            background: #fff;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,.15);
        }

        footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 13px;
        }

    </style>

</head>

<body>

<header>

    <div class="user">

        <?php if ($user): ?>

            Inloggad som
            <strong><?= htmlspecialchars($user['username']) ?></strong>

            |

            <a href="/logout">Logga ut</a>

        <?php endif; ?>

    </div>

    <h1>C64 Archive Manager</h1>

    <?php if ($user): ?>

        <nav>

            <a href="/">Dashboard</a>

            <a href="/disks">Diskar</a>

            <a href="/search">Sök</a>

        </nav>

    <?php endif; ?>

</header>

<div class="container">

    <?= $content ?>

</div>

<footer>

    C64 Archive Manager &copy; <?= date('Y') ?>

</footer>

</body>

</html>

