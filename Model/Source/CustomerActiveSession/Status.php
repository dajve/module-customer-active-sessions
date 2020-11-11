<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Model\Source\CustomerActiveSession;

use Dajve\CustomerActiveSessions\Model\Source\OptionHashInterface;
use Dajve\CustomerActiveSessions\Model\Source\OptionHashTrait;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 * @package Dajve\CustomerActiveSessions\Model\Source
 * @author Dajve Green <me@dajve.co.uk>
 */
class Status implements
    OptionSourceInterface,
    OptionHashInterface
{
    use OptionHashTrait;

    public const ACTIVE = 1;
    public const LOGGED_OUT = 2;
    public const TIMED_OUT = 3;
    public const TERMINATED_BY_CUSTOMER = 4;
    public const TERMINATED_BY_CONCURRENT_LOGIN = 5;

    /**
     * @return array[]
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => static::ACTIVE,
                'label' => __('Active'),
            ], [
                'value' => static::LOGGED_OUT,
                'label' => __('Logged out'),
            ], [
                'value' => static::TIMED_OUT,
                'label' => __('Timed out due to inactivity'),
            ], [
                'value' => static::TERMINATED_BY_CUSTOMER,
                'label' => __('Terminated manually'),
            ], [
                'value' => static::TERMINATED_BY_CONCURRENT_LOGIN,
                'label' => __('Terminated due to concurrent login'),
            ],
        ];
    }
}
