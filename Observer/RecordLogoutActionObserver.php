<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Observer;

use Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;
use Dajve\CustomerActiveSessions\Api\Service\RecordTerminatedActiveSessionInterface as RecordTerminatedActiveSessionServiceInterface; // phpcs:ignore Magento2.Files.LineLength.MaxExceeded
use Dajve\CustomerActiveSessions\Model\ResourceModel\CustomerActiveSession as CustomerActiveSessionResource;
use Dajve\CustomerActiveSessions\Model\Source\CustomerActiveSession\Status as StatusSource;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Customer as CustomerModel;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class RecordLogoutActionObserver
 * @package Dajve\CustomerActiveSessions\Observer
 * @author Dajve Green <me@dajve.co.uk>
 */
class RecordLogoutActionObserver implements ObserverInterface
{
    /**
     * @var SessionManagerInterface
     */
    private $sessionManager;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var RecordTerminatedActiveSessionServiceInterface
     */
    private $recordTerminatedActiveSessionService;

    /**
     * @var CustomerActiveSessionResource
     */
    private $customerActiveSessionResource;

    /**
     * RecordLogoutActionObserver constructor.
     * @param SessionManagerInterface $sessionManager
     * @param StoreManagerInterface $storeManager
     * @param RecordTerminatedActiveSessionServiceInterface $recordTerminatedActiveSessionService
     * @param CustomerActiveSessionResource $customerActiveSessionResource
     */
    public function __construct(
        SessionManagerInterface $sessionManager,
        StoreManagerInterface $storeManager,
        RecordTerminatedActiveSessionServiceInterface $recordTerminatedActiveSessionService,
        CustomerActiveSessionResource $customerActiveSessionResource
    ) {
        $this->sessionManager = $sessionManager;
        $this->storeManager = $storeManager;
        $this->recordTerminatedActiveSessionService = $recordTerminatedActiveSessionService;
        $this->customerActiveSessionResource = $customerActiveSessionResource;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $customer = $observer->getDataUsingMethod('customer');
        if ((!($customer instanceof CustomerInterface) && !($customer instanceof CustomerModel))
            || !$customer->getId()) {
            return;
        }

        $sessionId = $this->sessionManager->getSessionId();
        if (!$sessionId
            || $this->customerActiveSessionResource->sessionIdExists($sessionId, StatusSource::GROUP_TERMINATED)) {
            return;
        }

        try {
            $store = $this->storeManager->getStore();
            $storeId = (int)$store->getId();
        } catch (\Exception $e) {
            $storeId = 0;
        }

        $this->recordTerminatedActiveSessionService->recordTerminatedActiveSession(
            $sessionId,
            (int)$customer->getId(),
            StatusSource::LOGGED_OUT,
            [
                CustomerActiveSessionInterface::LAST_STORE_ID => $storeId,
            ]
        );
    }
}
