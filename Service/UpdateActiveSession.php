<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Service;

use Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;
use Dajve\CustomerActiveSessions\Api\Service\UpdateActiveSessionInterface;
use Dajve\CustomerActiveSessions\Helper\DateTimeHelper;
use Dajve\CustomerActiveSessions\Model\ResourceModel\CustomerActiveSession as CustomerActiveSessionResource;
use Dajve\CustomerActiveSessions\Model\Source\CustomerActiveSession\Status as StatusSource;
use Magento\Framework\Exception\AbstractAggregateException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

/**
 * Class UpdateActiveSession
 * @package Dajve\CustomerActiveSessions\Service
 * @author Dajve Green <me@dajve.co.uk>
 */
class UpdateActiveSession implements UpdateActiveSessionInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var DateTimeHelper
     */
    private $dateTimeHelper;

    /**
     * @var CustomerActiveSessionResource
     */
    private $customerActiveSessionResource;

    /**
     * UpdateActiveSession constructor.
     * @param LoggerInterface $logger
     * @param DateTimeHelper $dateTimeHelper
     * @param CustomerActiveSessionResource $customerActiveSessionResource
     */
    public function __construct(
        LoggerInterface $logger,
        DateTimeHelper $dateTimeHelper,
        CustomerActiveSessionResource $customerActiveSessionResource
    ) {
        $this->logger = $logger;
        $this->dateTimeHelper = $dateTimeHelper;
        $this->customerActiveSessionResource = $customerActiveSessionResource;
    }

    /**
     * @param string $sessionId
     * @param int $customerId
     * @param array $additionalData
     * @return bool
     */
    public function updateActiveSession(string $sessionId, int $customerId, array $additionalData = []): bool
    {
        $return = false;

        try {
            $return = $this->customerActiveSessionResource->updateBySessionAndCustomerId(
                $sessionId,
                $customerId,
                $this->collateSessionData($sessionId, $customerId, $additionalData)
            );
        } catch (NoSuchEntityException $e) {
            $this->logger->warning(sprintf(
                'Could not update data for session_id: "%s", customer_id: "%s": No matching session found',
                $sessionId,
                $customerId
            ));
        } catch (AbstractAggregateException $e) {
            $this->logger->error($e->getMessage(), ['originalException' => $e]);
            foreach ($e->getErrors() as $error) {
                $this->logger->error($error->getMessage(), ['originalException' => $e]);
            }
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
    public function collateSessionData(
        string $sessionId,
        int $customerId,
        array $additionalData
    ): ?array {
        $nowFormatted = $this->dateTimeHelper->getDateTimeFormatted('Y-m-d H:i:s', 'now', 'UTC');
        if (!$nowFormatted) {
            return null;
        }

        return array_merge(
            [
                CustomerActiveSessionInterface::LAST_ACTIVITY_TIME => $nowFormatted,
            ],
            array_intersect_key($additionalData, [
                CustomerActiveSessionInterface::USER_AGENT => true,
                CustomerActiveSessionInterface::LAST_STORE_ID => true,
                CustomerActiveSessionInterface::REMOTE_IP => true,
                CustomerActiveSessionInterface::LAST_ACTIVITY_TIME => true,
            ]),
            [
                CustomerActiveSessionInterface::SESSION_ID => $sessionId,
                CustomerActiveSessionInterface::CUSTOMER_ID => $customerId,
                CustomerActiveSessionInterface::STATUS => StatusSource::ACTIVE,
            ]
        );
    }
}
