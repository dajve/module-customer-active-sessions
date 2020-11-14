<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Controller\Sessions;

use Dajve\CustomerActiveSessions\CommonVars;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\View\Element\Html\Links as LinksBlock;
use Magento\Framework\View\Page\Title as PageTitle;
use Magento\Framework\View\Result\Page as ResultPage;
use Magento\Review\Controller\Customer as CustomerController;

/**
 * Class Index
 * @package Dajve\CustomerActiveSessions\Controller
 * @author Dajve Green <me@dajve.co.uk>
 */
class Index extends CustomerController
{
    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        /** @var ResultPage $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $navigationBlock = $this->getBlock('customer_account_navigation', $resultPage);
        if ($navigationBlock instanceof LinksBlock) {
            $navigationBlock->setActive(CommonVars::ROUTE_CUSTOMER_ACCOUNT_SESSIONS_LIST);
        }

        $pageTitle = $this->getPageTitle($resultPage);
        if ($pageTitle) {
            $pageTitle->set(__('My Active Sessions'));
        }

        return $resultPage;
    }

    /**
     * @param string $blockName
     * @param ResultPage $resultPage
     * @return BlockInterface|null
     */
    private function getBlock(string $blockName, ResultPage $resultPage): ?BlockInterface
    {
        $layout = $resultPage->getLayout();

        return $layout->getBlock($blockName) ?: null;
    }

    /**
     * @param ResultPage $resultPage
     * @return PageTitle|null
     */
    private function getPageTitle(ResultPage $resultPage): ?PageTitle
    {
        $config = $resultPage->getConfig();

        return $config ? $config->getTitle() : null;
    }
}
