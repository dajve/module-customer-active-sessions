<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Helper;

use Psr\Log\LoggerInterface;

/**
 * Class DateTimeHelper
 * @package Dajve\CustomerActiveSessions\Helper
 * @author Dajve Green <me@dajve.co.uk>
 */
class DateTimeHelper
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * DateTimeHelper constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $format
     * @param string $time
     * @param string $timezone
     * @return string|null
     */
    public function getDateTimeFormatted(
        string $format = 'Y-m-d H:i:s',
        string $time = 'now',
        string $timezone = 'UTC'
    ): ?string {
        $return = null;

        try {
            $dateTime = new \DateTime($time, new \DateTimeZone($timezone));
            $return = $dateTime->format($format);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return $return;
    }
}
