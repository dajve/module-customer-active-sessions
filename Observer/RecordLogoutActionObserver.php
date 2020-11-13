<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Observer;

use Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;
use Dajve\CustomerActiveSessions\Api\Service\RecordTerminatedActiveSessionInterface as RecordTerminatedActiveSessionServiceInterface; // phpcs:ignore Magento2.Files.LineLength.MaxExceeded
use Dajve\CustomerActiveSessions\Model\Source\CustomerActiveSession\Status as StatusSource;
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
     * RecordLogoutActionObserver constructor.
     * @param SessionManagerInterface $sessionManager
     * @param StoreManagerInterface $storeManager
     * @param RecordTerminatedActiveSessionServiceInterface $recordTerminatedActiveSessionService
     */
    public function __construct(
        SessionManagerInterface $sessionManager,
        StoreManagerInterface $storeManager,
        RecordTerminatedActiveSessionServiceInterface $recordTerminatedActiveSessionService
    ) {
        $this->sessionManager = $sessionManager;
        $this->storeManager = $storeManager;
        $this->recordTerminatedActiveSessionService = $recordTerminatedActiveSessionService;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $customer = $observer->getDataUsingMethod('customer');
        if (!$customer || !$customer->getId()) {
            return;
        }

        $sessionId = $this->sessionManager->getSessionId();
        if (!$sessionId) {
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
