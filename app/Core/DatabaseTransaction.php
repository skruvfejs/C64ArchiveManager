<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use Throwable;

final class DatabaseTransaction
{
    public function __construct(
        private PDO $pdo
    ) {
    }


    public function begin(): void
    {
        if (!$this->pdo->inTransaction()) {

            $this->pdo->beginTransaction();

        }
    }


    public function commit(): void
    {
        if ($this->pdo->inTransaction()) {

            $this->pdo->commit();

        }
    }


    public function rollback(): void
    {
        if ($this->pdo->inTransaction()) {

            $this->pdo->rollBack();

        }
    }


    /**
     * Kör en callback atomiskt.
     *
     * Vid lyckat resultat:
     * COMMIT
     *
     * Vid exception:
     * ROLLBACK
     */
    public function run(
        callable $callback
    ): mixed {

        try {

            $this->begin();


            $result =
                $callback();


            $this->commit();


            return $result;


        } catch (Throwable $e) {


            $this->rollback();


            throw $e;
        }
    }
}

