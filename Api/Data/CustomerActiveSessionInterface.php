<?php
/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
// phpcs:disable Magento2.Files.LineLength.MaxExceeded

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface CustomerActiveSessionInterface
 * @package Dajve\CustoemrActiveSessions\Api\Data
 * @author Dajve Green <me@dajve.co.uk>
 */
interface CustomerActiveSessionInterface extends ExtensibleDataInterface
{
    public const ID = 'id';
    public const SESSION_ID = 'session_id';
    public const CUSTOMER_ID = 'customer_id';
    public const START_TIME = 'start_time';
    public const LAST_ACTIVITY_TIME = 'last_activity_time';
    public const TERMINATION_TIME = 'termination_time';
    public const INITIAL_STORE_ID = 'initial_store_id';
    public const LAST_STORE_ID = 'last_store_id';
    public const STATUS = 'status';
    public const REMOTE_IP = 'remote_ip';
    public const USER_AGENT = 'user_agent';

    /**
     * @param int $id
     * @return mixed
     * @noinspection PhpMissingParamTypeInspection
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getId();

    /**
     * @param string $sessionId
     * @return \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface
     */
    public function setSessionId(string $sessionId): \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;

    /**
     * @return string
     */
    public function getSessionId(): string;

    /**
     * @param int $customerId
     * @return \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface
     */
    public function setCustomerId(int $customerId): \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;

    /**
     * @return int
     */
    public function getCustomerId(): int;

    /**
     * @param string $startTime
     * @return \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface
     */
    public function setStartTime(string $startTime): \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;

    /**
     * @return string
     */
    public function getStartTime(): string;

    /**
     * @param string $lastActivityTime
     * @return \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface
     */
    public function setLastActivityTime(string $lastActivityTime): \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;

    /**
     * @return string
     */
    public function getLastActivityTime(): string;

    /**
     * @param string $terminationTime
     * @return \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface
     */
    public function setTerminationTime(string $terminationTime): \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;

    /**
     * @return string|null
     */
    public function getTerminationTime(): ?string;

    /**
     * @param int $initialStoreId
     * @return \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface
     */
    public function setInitialStoreId(int $initialStoreId): \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;

    /**
     * @return int
     */
    public function getInitialStoreId(): int;

    /**
     * @param int $lastStoreId
     * @return \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface
     */
    public function setLastStoreId(int $lastStoreId): \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;

    /**
     * @return int
     */
    public function getLastStoreId(): int;

    /**
     * @param int $status
     * @return \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface
     */
    public function setStatus(int $status): \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;

    /**
     * @return int
     */
    public function getStatus():int;

    /**
     * @param string $remoteIp
     * @return \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface
     */
    public function setRemoteIp(string $remoteIp): \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;

    /**
     * @return string
     */
    public function getRemoteIp(): string;

    /**
     * @param string $userAgent
     * @return \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface
     */
    public function setUserAgent(string $userAgent): \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;

    /**
     * @return string
     */
    public function getUserAgent(): string;

    /**
     * @param \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionExtensionInterface $extensionAttributes
     * @return \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface
     */
    public function setExtensionAttributes(\Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionExtensionInterface $extensionAttributes): \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;

    /**
     * @return \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionExtensionInterface
     */
    public function getExtensionAttributes(): \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionExtensionInterface;
}
