<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Api\Service;

/**
 * Interface UpdateActiveSessionInterface
 * @package Dajve\CustomerActiveSessions\Api\Service
 * @author Dajve Green <me@dajve.co.uk>
 */
interface UpdateActiveSessionInterface
{
    /**
     * @param string $sessionId
     * @param int $customerId
     * @param array $additionalData
     * @return bool
     */
    public function updateActiveSession(string $sessionId, int $customerId, array $additionalData = []): bool;
}
