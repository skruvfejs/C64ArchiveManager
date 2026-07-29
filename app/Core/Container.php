<?php

declare(strict_types=1);

namespace App\Core;

use Closure;
use ReflectionClass;
use RuntimeException;

final class Container
{
    /**
     * Registrerade factories.
     *
     * @var array<string, Closure>
     */
    private array $bindings = [];

    /**
     * Singleton-instanser.
     *
     * @var array<string, object>
     */
    private array $instances = [];

    public function singleton(string $id, Closure $factory): void
    {
        $this->bindings[$id] = $factory;
    }

    public function has(string $id): bool
    {
        return isset($this->instances[$id])
            || isset($this->bindings[$id]);
    }

    public function get(string $id): object
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (isset($this->bindings[$id])) {

            $object = ($this->bindings[$id])($this);

            $this->instances[$id] = $object;

            return $object;
        }

        return $this->make($id);
    }

    public function make(string $class): object
    {
        $reflection = new ReflectionClass($class);

        if (!$reflection->isInstantiable()) {
            throw new RuntimeException(
                sprintf(
                    'Class "%s" is not instantiable.',
                    $class
                )
            );
        }

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return new $class();
        }

        $arguments = [];

        foreach ($constructor->getParameters() as $parameter) {

            $type = $parameter->getType();

            if ($type === null || $type->isBuiltin()) {
                throw new RuntimeException(
                    sprintf(
                        'Unable to resolve parameter $%s in %s.',
                        $parameter->getName(),
                        $class
                    )
                );
            }

            $arguments[] = $this->get(
                $type->getName()
            );
        }

        return $reflection->newInstanceArgs($arguments);
    }
}

