<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Api;

use Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;

/**
 * Interface CustomerActiveSessionIteratorInterface
 * @package Dajve\CustomerActiveSessions\Api
 * @author Dajve Green <me@dajve.co.uk>
 */
interface CustomerActiveSessionIteratorInterface extends \Iterator
{
    /**
     * @return CustomerActiveSessionInterface
     */
    public function current(): CustomerActiveSessionInterface;

    /**
     * @return int
     */
    public function key(): int;

    /**
     * @return bool
     */
    public function valid(): bool;

    /**
     * @return CustomerActiveSessionInterface[]
     */
    public function toArray(): array;
}
