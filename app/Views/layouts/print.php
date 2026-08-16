<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">

    <title>
        <?= htmlspecialchars(
            $title ?? 'C64 Archive Manager'
        ) ?>
    </title>

    <style>
<?php
$printCss =
    __DIR__ . '/print.css';

if (file_exists($printCss)) {
    readfile($printCss);
}
?>
    </style>
</head>

<body>

<?= $content ?>

</body>
</html>
