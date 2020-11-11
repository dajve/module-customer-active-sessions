<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Model\Source;

/**
 * Interface OptionHashInterface
 * @package Dajve\CustomerActiveSessions\Model\Source
 * @author Dajve Green <me@dajve.co.uk>
 */
interface OptionHashInterface
{
    /**
     * @return array
     */
    public function toOptionHash(): array;
}
