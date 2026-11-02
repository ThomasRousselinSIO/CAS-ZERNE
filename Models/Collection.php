<?php

declare(strict_types=1);

/**
 * Collection générique correspondant à la classe technique de l'annexe D.
 */
final class Collection implements IteratorAggregate, Countable
{
    /** @var array<int, mixed> */
    private array $elements = [];

    public function cardinal(): int
    {
        return count($this->elements);
    }

    public function contient(mixed $element): bool
    {
        return in_array($element, $this->elements, true);
    }

    public function index(mixed $element): int
    {
        $index = array_search($element, $this->elements, true);
        return $index === false ? -1 : $index;
    }

    public function extraire(int $index): mixed
    {
        if (!array_key_exists($index, $this->elements)) {
            throw new OutOfBoundsException("Index de collection invalide : $index.");
        }

        return $this->elements[$index];
    }

    public function ajouter(mixed $element): void
    {
        $this->elements[] = $element;
    }

    public function enlever(mixed $element): void
    {
        $index = $this->index($element);
        if ($index !== -1) {
            array_splice($this->elements, $index, 1);
        }
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->elements);
    }

    public function count(): int
    {
        return $this->cardinal();
    }
}
