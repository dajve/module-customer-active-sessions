<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Service;

use Dajve\CustomerActiveSessions\Api\CustomerActiveSessionRepositoryInterface;
use Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;
use Dajve\CustomerActiveSessions\Api\Service\RecordTerminatedActiveSessionInterface;
use Dajve\CustomerActiveSessions\Helper\DateTimeHelper;
use Dajve\CustomerActiveSessions\Helper\GetterSetterHelper;
use Dajve\CustomerActiveSessions\Model\Source\CustomerActiveSession\Status as StatusSource;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

/**
 * Class RecordTerminatedActiveSession
 * @package Dajve\CustomerActiveSessions\Service
 * @author Dajve Green <me@dajve.co.uk>
 */
class RecordTerminatedActiveSession implements RecordTerminatedActiveSessionInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var GetterSetterHelper
     */
    private $getterSetterHelper;

    /**
     * @var DateTimeHelper
     */
    private $dateTimeHelper;

    /**
     * @var CustomerActiveSessionRepositoryInterface
     */
    private $customerActiveSessionRepository;

    /**
     * RecordTerminatedActiveSession constructor.
     * @param LoggerInterface $logger
     * @param GetterSetterHelper $getterSetterHelper
     * @param DateTimeHelper $dateTimeHelper
     * @param CustomerActiveSessionRepositoryInterface $customerActiveSessionRepository
     */
    public function __construct(
        LoggerInterface $logger,
        GetterSetterHelper $getterSetterHelper,
        DateTimeHelper $dateTimeHelper,
        CustomerActiveSessionRepositoryInterface $customerActiveSessionRepository
    ) {
        $this->logger = $logger;
        $this->getterSetterHelper = $getterSetterHelper;
        $this->dateTimeHelper = $dateTimeHelper;
        $this->customerActiveSessionRepository = $customerActiveSessionRepository;
    }

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
    ): bool {
        $return = false;
        try {
            $customerActiveSession = $this->customerActiveSessionRepository->getBySessionId($sessionId);
        } catch (NoSuchEntityException $e) {
            $this->logger->notice(sprintf(
                'Attempted to record customer session termination with session id "%s": no session found',
                $sessionId
            ));

            return $return;
        }

        if ($customerActiveSession->getCustomerId() !== $customerId) {
            $this->logger->notice(sprintf(
                'Attempted to record customer session termination for customer_id: %s, session_id: %s: session does not belong to customer', // phpcs:ignore Magento2.Files.LineLength.MaxExceeded
                $customerId,
                $sessionId
            ));

            return $return;
        }

        $data = $this->collateSessionData($sessionId, $customerId, $status, $additionalData);
        foreach ($data as $field => $value) {
            try {
                $this->getterSetterHelper->setData($customerActiveSession, $field, $value);
            } catch (\BadMethodCallException $e) {
                $this->logger->error($e->getMessage(), ['originalException' => $e]);
            }
        }

        try {
            $this->customerActiveSessionRepository->save($customerActiveSession);
            $return = true;
        } catch (AlreadyExistsException | CouldNotSaveException $e) {
            $this->logger->error($e->getMessage(), ['originalException' => $e]);
        }

        return $return;
    }

    /**
     * @param string $sessionId
     * @param int $customerId
     * @param int $status
     * @param array $additionalData
     * @return array|null
     */
    public function collateSessionData(
        string $sessionId,
        int $customerId,
        int $status,
        array $additionalData
    ): ?array {
        $nowFormatted = $this->dateTimeHelper->getDateTimeFormatted('Y-m-d H:i:s', 'now', 'UTC');
        if (!$nowFormatted) {
            return null;
        }

        $allowedStatuses = $this->getAllowedStatuses();
        if (!in_array($status, $allowedStatuses, true)) {
            $this->logger->error(sprintf(
                'Invalid status for terminated customer session "%s". Expected: %s',
                $status,
                implode(',', $allowedStatuses)
            ));

            return null;
        }

        return array_merge(
            [
                CustomerActiveSessionInterface::TERMINATION_TIME => $nowFormatted,
            ],
            array_diff_key($additionalData, [
                CustomerActiveSessionInterface::ID => true,
                CustomerActiveSessionInterface::START_TIME => true,
                CustomerActiveSessionInterface::INITIAL_STORE_ID => true,
            ]),
            [
                CustomerActiveSessionInterface::SESSION_ID => $sessionId,
                CustomerActiveSessionInterface::CUSTOMER_ID => $customerId,
                CustomerActiveSessionInterface::STATUS => $status,
            ]
        );
    }

    /**
     * @return int[]
     */
    public function getAllowedStatuses(): array
    {
        return [
            StatusSource::LOGGED_OUT,
            StatusSource::TIMED_OUT,
            StatusSource::TERMINATED_BY_CUSTOMER,
            StatusSource::TERMINATED_BY_CONCURRENT_LOGIN,
        ];
    }
}
