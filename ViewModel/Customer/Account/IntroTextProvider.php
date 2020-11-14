<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\ViewModel\Customer\Account;

use Dajve\CustomerActiveSessions\CommonVars;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class IntroTextProvider
 * @package Dajve\CustomerActiveSessions\ViewModel
 * @author Dajve Green <me@dajve.co.uk>
 */
class IntroTextProvider implements ArgumentInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * IntroTextProvider constructor.
     * @param LoggerInterface $logger
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * @return string
     */
    public function getIntroText(): string
    {
        try {
            $store = $this->storeManager->getStore();
            $storeId = (int)$store->getId();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['originalException' => $e]);
            $storeId = 0;
        }

        return trim((string)$this->scopeConfig->getValue(
            CommonVars::XML_PATH_CUSTOMER_ACCOUNT_INTRO_TEXT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ));
    }
}
