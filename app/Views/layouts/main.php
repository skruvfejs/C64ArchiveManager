<?php

declare(strict_types=1);

use App\Core\Auth;
use App\Core\Session;
use App\Core\Flash;
use App\Core\Authorization;
use App\Core\Permission;
use App\Services\SettingsService;


$session = new Session();

$auth = new Auth($session);


$authorization =
    new Authorization($auth);


$user = $auth->user();


$flash =
    new Flash($session);


$message =
    $flash->get();


$settingsService =
    new SettingsService(
        new \App\Core\Database(
            new \App\Core\Config(
                dirname(__DIR__, 3) . '/config'
            )
        )
    );


$siteName =
    $settingsService->get(
        'site_name',
        'C64 Archive Manager'
    );


$title ??= $siteName;

?>
<!DOCTYPE html>
<html lang="sv">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= htmlspecialchars($title) ?>
    </title>

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


        .flash {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
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

            <?= htmlspecialchars(
                $language->get('logged_in_as')
            ) ?>

            <strong>
                <?= htmlspecialchars($user['username']) ?>
            </strong>


            |


            <a href="/account/password">
                <?= htmlspecialchars(
                    $language->get('change_password')
                ) ?>
            </a>


            |


            <a href="/logout">
                <?= htmlspecialchars(
                    $language->get('logout')
                ) ?>
            </a>


        <?php endif; ?>

    </div>


    <h1>
        <?= htmlspecialchars($siteName) ?>
    </h1>


    <?php if ($user): ?>

        <nav>

            <a href="/">
                <?= htmlspecialchars(
                    $language->get('dashboard')
                ) ?>
            </a>


            <a href="/disk">
                <?= htmlspecialchars(
                    $language->get('disks')
                ) ?>
            </a>


            <a href="/search">
                <?= htmlspecialchars(
                    $language->get('search')
                ) ?>
            </a>


            <?php if (
                $authorization->can(
                    Permission::MANAGE_USERS
                )
            ): ?>

                <a href="/administration">
                    <?= htmlspecialchars(
                        $language->get('administration')
                    ) ?>
                </a>

            <?php endif; ?>

        </nav>

    <?php endif; ?>


</header>


<div class="container">


    <?php if ($message): ?>

        <div class="flash">

            <?= htmlspecialchars(
                $message['message']
            ) ?>

        </div>

    <?php endif; ?>


    <?= $content ?>


</div>


<footer>

    <?= htmlspecialchars($siteName) ?>
    &copy; <?= date('Y') ?>

</footer>


</body>

</html>
