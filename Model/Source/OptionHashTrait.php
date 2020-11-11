<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Model\Source;

/**
 * Trait OptionHashTrait
 * @package Dajve\CustomerActiveSessions\Model\Source
 * @author Dajve Green <me@dajve.co.uk>
 */
trait OptionHashTrait
{
    /**
     * @return array
     */
    public function toOptionHash(): array
    {
        if (!method_exists($this, 'toOptionArray')) {
            return [];
        }

        $options = $this->toOptionArray();

        return array_combine(
            array_column($options, 'value'),
            array_column($options, 'label')
        );
    }
}
