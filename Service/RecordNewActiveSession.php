<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Service;

use Dajve\CustomerActiveSessions\Api\CustomerActiveSessionRepositoryInterface;
use Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;
use Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterfaceFactory;
use Dajve\CustomerActiveSessions\Api\Service\RecordNewActiveSessionInterface;
use Dajve\CustomerActiveSessions\Model\Source\CustomerActiveSession\Status as StatusSource;
use Magento\Framework\Exception\AlreadyExistsException;
use Psr\Log\LoggerInterface;

/**
 * Class RecordNewActiveSession
 * @package Dajve\CustomerActiveSessions\Service
 * @author Dajve Green <me@dajve.co.uk>
 */
class RecordNewActiveSession implements RecordNewActiveSessionInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var CustomerActiveSessionInterfaceFactory
     */
    private $customerActiveSessionFactory;

    /**
     * @var CustomerActiveSessionRepositoryInterface
     */
    private $customerActiveSessionRepository;

    /**
     * RecordNewActiveSession constructor.
     * @param LoggerInterface $logger
     * @param CustomerActiveSessionInterfaceFactory $customerActiveSessionFactory
     * @param CustomerActiveSessionRepositoryInterface $customerActiveSessionRepository
     */
    public function __construct(
        LoggerInterface $logger,
        CustomerActiveSessionInterfaceFactory $customerActiveSessionFactory,
        CustomerActiveSessionRepositoryInterface $customerActiveSessionRepository
    ) {
        $this->logger = $logger;
        $this->customerActiveSessionFactory = $customerActiveSessionFactory;
        $this->customerActiveSessionRepository = $customerActiveSessionRepository;
    }

    /**
     * @param string $sessionId
     * @param int $customerId
     * @param array $additionalData
     * @return bool
     */
    public function recordNewActiveSession(string $sessionId, int $customerId, array $additionalData = []): bool
    {
        $return = false;
        $data = $this->collateSessionData($sessionId, $customerId, $additionalData);
        if (!$data) {
            return $return;
        }

        try {
            $customerActiveSession = $this->customerActiveSessionFactory->create(['data' => $data]);
            if (method_exists($customerActiveSession, 'setHasDataChanges')) {
                $customerActiveSession->setHasDataChanges(true);
            }
            $this->customerActiveSessionRepository->save($customerActiveSession);
            $return = true;
        } catch (AlreadyExistsException $e) {
            $this->logger->warning($e->getMessage(), ['originalException' => $e]);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['originalException' => $e]);
        }

        return $return;
    }

    /**
     * @param string $sessionId
     * @param int $customerId
     * @param array $additionalData
     * @return array|null
     */
    public function collateSessionData(string $sessionId, int $customerId, array $additionalData): ?array
    {
        try {
            $now = new \DateTime('now', new \DateTimeZone('UTC'));
            $nowFormatted = $now->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());

            return null;
        }

        return array_merge(
            [
                CustomerActiveSessionInterface::START_TIME => $nowFormatted,
                CustomerActiveSessionInterface::LAST_ACTIVITY_TIME => $nowFormatted,
            ],
            $additionalData,
            [
                CustomerActiveSessionInterface::SESSION_ID => $sessionId,
                CustomerActiveSessionInterface::CUSTOMER_ID => $customerId,
                CustomerActiveSessionInterface::STATUS => StatusSource::ACTIVE,
                CustomerActiveSessionInterface::TERMINATION_TIME => null,
            ]
        );
    }
}
