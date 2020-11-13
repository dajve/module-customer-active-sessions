<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Api\Service;

/**
 * Interface RecordNewActiveSessionInterface
 * @package Dajve\CustomerActiveSessions\Api\Service
 * @author Dajve Green <me@dajve.co.uk>
 */
interface RecordNewActiveSessionInterface
{
    /**
     * @param string $sessionId
     * @param int $customerId
     * @return bool
     */
    public function recordNewActiveSession(string $sessionId, int $customerId, array $additionalData = []): bool;
}
