<?php

declare(strict_types=1);

return [

    'up' => function (\PDO $pdo): void {

        $stmt = $pdo->prepare(
            'SELECT id
             FROM users
             WHERE username = :username
             LIMIT 1'
        );

        $stmt->execute([
            'username' => 'screwface'
        ]);

        if ($stmt->fetch()) {
            return;
        }

        $stmt = $pdo->prepare(
            'INSERT INTO users
            (
                role_id,
                username,
                email,
                password,
                first_name,
                last_name,
                active
            )
            VALUES
            (
                :role_id,
                :username,
                :email,
                :password,
                :first_name,
                :last_name,
                :active
            )'
        );

        $stmt->execute([
            'role_id'    => 1,
            'username'   => 'screwface',
            'email'      => 'admin@localhost',
            'password'   => password_hash('admin', PASSWORD_DEFAULT),
            'first_name' => '',
            'last_name'  => '',
            'active'     => 1,
        ]);
    },

    'down' => function (\PDO $pdo): void {

        $stmt = $pdo->prepare(
            'DELETE FROM users
             WHERE username = :username'
        );

        $stmt->execute([
            'username' => 'screwface'
        ]);
    }

];
