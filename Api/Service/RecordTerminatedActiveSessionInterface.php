<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Api\Service;

/**
 * Interface RecordTerminatedActiveSessionInterface
 * @package Dajve\CustomerActiveSessions\Api\Service
 * @author Dajve Green <me@dajve.co.uk>
 */
interface RecordTerminatedActiveSessionInterface
{
    /**
     * @param string $sessionId
     * @param int $customerId
     * @param int $status
     * @param array $additionalData
     * @return bool
     */
    public function recordTerminatedActiveSession(
        string $sessionId,
        int $customerId,
        int $status,
        array $additionalData = []
    ): bool;
}
