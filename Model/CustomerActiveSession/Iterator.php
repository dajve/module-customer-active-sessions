<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Model\CustomerActiveSession;

use Dajve\CustomerActiveSessions\Api\CustomerActiveSessionIteratorInterface;
use Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;

/**
 * Class Iterator
 * @package Dajve\CustomerActiveSessions\Model
 * @author Dajve Green <me@dajve.co.uk>
 */
class Iterator implements CustomerActiveSessionIteratorInterface
{
    /**
     * @var int
     */
    private $position = 0;

    /**
     * @var array
     */
    private $data = [];

    /**
     * Iterator constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        array_walk($data, [$this, 'addItem']);
    }

    /**
     * @param CustomerActiveSessionInterface $item
     */
    private function addItem(CustomerActiveSessionInterface $item): void
    {
        $this->data[] = $item;
    }

    /**
     * @return void
     */
    public function next(): void
    {
        ++$this->position;
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return ($this->data[$this->key()] ?? null) instanceof CustomerActiveSessionInterface;
    }

    /**
     * @return void
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * @return CustomerActiveSessionInterface
     */
    public function current(): CustomerActiveSessionInterface
    {
        return $this->data[$this->key()];
    }

    /**
     * @return CustomerActiveSessionInterface[]
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
