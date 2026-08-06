<?php

declare(strict_types=1);

return [

    'up' => function (PDO $pdo): void {

        // Intentionally left empty.
        //
        // file_offset and file_size were moved into
        // 20260729_009_create_directory_entries_table.php
        // so that a fresh installation creates the complete
        // schema directly.

    },

    'down' => function (PDO $pdo): void {

        // Nothing to rollback.

    }

];
