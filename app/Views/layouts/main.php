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


$theme ??= 'system';


?>
<!DOCTYPE html>
<html
    lang="<?= htmlspecialchars($language->language()) ?>"
    data-theme="<?= htmlspecialchars($theme) ?>"
>

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


        :root {
            color-scheme: light dark;
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


        /*
         * Dark theme
         */

        html[data-theme="dark"] body {
            background: #181818;
            color: #eee;
        }


        html[data-theme="dark"] .container {
            background: #242424;
            color: #eee;
        }


        html[data-theme="dark"] .flash {
            border-color: #555;
        }


        html[data-theme="dark"] footer {
            color: #aaa;
        }


        /*
         * System theme
         */

        @media (prefers-color-scheme: dark) {

            html[data-theme="system"] body {
                background: #181818;
                color: #eee;
            }


            html[data-theme="system"] .container {
                background: #242424;
                color: #eee;
            }


            html[data-theme="system"] .flash {
                border-color: #555;
            }


            html[data-theme="system"] footer {
                color: #aaa;
            }

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


            <a href="/account/settings">
                <?= htmlspecialchars(
                    $language->get('user_settings')
                ) ?>
            </a>


            |


            <a href="/logout">
                <?= htmlspecialchars(
                    $language->get('logout')
                ) ?>
            </a>


        <?php else: ?>

            <a href="/language/en">
                <?= htmlspecialchars(
                    $language->get('english')
                ) ?>
            </a>

            |

            <a href="/language/sv">
                <?= htmlspecialchars(
                    $language->get('swedish')
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


            <a href="/entry">
                <?= htmlspecialchars(
                    $language->get('archive')
                ) ?>
            </a>


            <a href="/disk">
                <?= htmlspecialchars(
                    $language->get('disks')
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
