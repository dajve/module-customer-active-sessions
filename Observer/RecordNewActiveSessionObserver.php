<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Observer;

use Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;
use Dajve\CustomerActiveSessions\Api\Service\RecordNewActiveSessionInterface as RecordNewActiveSessionServiceInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\HTTP\Header as HttpHeader;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class RecordNewActiveSessionObserver
 * @package Dajve\CustomerActiveSessions\Observer
 * @author Dajve Green <me@dajve.co.uk>
 */
class RecordNewActiveSessionObserver implements ObserverInterface
{
    /**
     * @var SessionManagerInterface
     */
    private $sessionManager;

    /**
     * @var RemoteAddress
     */
    private $remoteAddress;

    /**
     * @var HttpHeader
     */
    private $httpHeader;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var RecordNewActiveSessionServiceInterface
     */
    private $recordNewActiveSessionService;

    /**
     * RecordNewActiveSessionObserver constructor.
     * @param SessionManagerInterface $sessionManager
     * @param RemoteAddress $remoteAddress
     * @param HttpHeader $httpHeader
     * @param StoreManagerInterface $storeManager
     * @param RecordNewActiveSessionServiceInterface $recordNewActiveSessionService
     */
    public function __construct(
        SessionManagerInterface $sessionManager,
        RemoteAddress $remoteAddress,
        HttpHeader $httpHeader,
        StoreManagerInterface $storeManager,
        RecordNewActiveSessionServiceInterface $recordNewActiveSessionService
    ) {
        $this->sessionManager = $sessionManager;
        $this->remoteAddress = $remoteAddress;
        $this->httpHeader = $httpHeader;
        $this->storeManager = $storeManager;
        $this->recordNewActiveSessionService = $recordNewActiveSessionService;
    }

    /**
     * @param Observer $observer
     * @return void
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

        $additionalData = [
            CustomerActiveSessionInterface::REMOTE_IP => $this->remoteAddress->getRemoteAddress(),
            CustomerActiveSessionInterface::USER_AGENT => $this->httpHeader->getHttpUserAgent(true),
            CustomerActiveSessionInterface::INITIAL_STORE_ID => $storeId,
            CustomerActiveSessionInterface::LAST_STORE_ID => $storeId,
        ];

        $this->recordNewActiveSessionService->recordNewActiveSession(
            $sessionId,
            (int)$customer->getId(),
            $additionalData
        );
    }
}
