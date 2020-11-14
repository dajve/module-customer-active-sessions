<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Observer;

use Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;
use Dajve\CustomerActiveSessions\Api\Service\UpdateActiveSessionInterface as UpdateActiveSessionServiceInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\HttpRequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class UpdateActiveSessionObserver
 * @package Dajve\CustomerActiveSessions\Observer
 * @author Dajve Green <me@dajve.co.uk>
 */
class UpdateActiveSessionObserver implements ObserverInterface
{
    /**
     * @var SessionManagerInterface
     */
    private $sessionManager;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var UpdateActiveSessionServiceInterface
     */
    private $updateActiveSessionService;

    /**
     * UpdateActiveSessionObserver constructor.
     * @param SessionManagerInterface $sessionManager
     * @param CustomerSession $customerSession
     * @param StoreManagerInterface $storeManager
     * @param UpdateActiveSessionServiceInterface $updateActiveSessionService
     */
    public function __construct(
        SessionManagerInterface $sessionManager,
        CustomerSession $customerSession,
        StoreManagerInterface $storeManager,
        UpdateActiveSessionServiceInterface $updateActiveSessionService
    ) {
        $this->sessionManager = $sessionManager;
        $this->customerSession = $customerSession;
        $this->storeManager = $storeManager;
        $this->updateActiveSessionService = $updateActiveSessionService;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        if (!$this->customerSession->isLoggedIn()) {
            return;
        }

        $request = $observer->getDataUsingMethod('request');
        if (!$request
            && $request instanceof HttpRequestInterface
            && method_exists($request, 'isAjax')
            && $request->isAjax()) {
            return;
        }

        $customerId = (int)$this->customerSession->getCustomerId();
        $sessionId = $this->sessionManager->getSessionId();
        if (!$customerId || !$sessionId) {
            return;
        }

        try {
            $store = $this->storeManager->getStore();
            $storeId = (int)$store->getId();
        } catch (\Exception $e) {
            $storeId = 0;
        }

        $this->updateActiveSessionService->updateActiveSession(
            $sessionId,
            $customerId,
            [
                CustomerActiveSessionInterface::LAST_STORE_ID => $storeId,
            ]
        );
    }
}
