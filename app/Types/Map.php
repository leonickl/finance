<?php

declare(strict_types=1);

namespace App\Types;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use RuntimeException;
use Traversable;

/**
 * This class aims to be a substitute for associative arrays if objects
 * want to be used as keys.
 * The class uses loose over strict comparison for the keys
 * in order to check object equality and not identity.
 */
final class Map implements ArrayAccess, Countable, IteratorAggregate
{
    private $data = [];

    private function __construct() {}

    public static function empty(): self
    {
        return new self;
    }

    public static function combine(array $keys, array $values): self
    {
        $map = self::empty();

        if (count($keys) !== count($values)) {
            throw new RuntimeException('Number of keys does not match number of values');
        }

        for ($i = 0; $i < count($keys); $i++) {
            $map[$keys[$i]] = $values[$i];
        }

        return $map;
    }

    public function offsetExists(mixed $offset): bool
    {
        foreach ($this->data as [$key, $value]) {
            if ($offset == $key) {
                return true;
            }
        }

        return false;
    }

    public function offsetGet(mixed $offset): mixed
    {
        foreach ($this->data as [$key, $value]) {
            if ($offset == $key) {
                return $value;
            }
        }

        throw new RuntimeException('Offset does not exist');
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        foreach ($this->data as $i => [$key, $oldValue]) {
            if ($offset == $key) {
                $this->data[$i] = [$key, $value];

                return;
            }
        }

        $this->data[] = [$offset, $value];
    }

    public function offsetUnset(mixed $offset): void
    {
        foreach ($this->data as $i => [$key, $value]) {
            if ($offset == $key) {
                unset($this->data[$i]);

                return;
            }
        }
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
    }

    public function has(mixed $offset): bool
    {
        return $this->offsetExists($offset);
    }

    public function values(): array
    {
        $values = [];

        foreach ($this->data as [$key, $value]) {
            $values[] = $value;
        }

        return $values;
    }
}
