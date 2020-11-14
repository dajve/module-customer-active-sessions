<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\ViewModel;

use Dajve\CustomerActiveSessions\Api\CustomerActiveSessionIteratorInterface;
use Dajve\CustomerActiveSessions\Api\CustomerActiveSessionIteratorInterfaceFactory;
use Dajve\CustomerActiveSessions\Api\CustomerActiveSessionRepositoryInterface;
use Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;
use Dajve\CustomerActiveSessions\Helper\DateTimeHelper;
use Dajve\CustomerActiveSessions\Helper\UserAgentParser;
use Dajve\CustomerActiveSessions\Model\Source\CustomerActiveSession\Status as StatusSource;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface as StdLibTimezoneInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Class SessionsProvider
 * @package Dajve\CustomerActiveSessions\ViewModel
 * @author Dajve Green <me@dajve.co.uk>
 */
class SessionsProvider implements ArgumentInterface
{
    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;

    /**
     * @var CustomerActiveSessionRepositoryInterface
     */
    private $customerActiveSessionRepository;

    /**
     * @var CustomerActiveSessionIteratorInterfaceFactory
     */
    private $customerActiveSessionsIteratorFactory;

    /**
     * @var CustomerActiveSessionIteratorInterface
     */
    private $customerActiveSessions;

    /**
     * @var DateTimeHelper
     */
    private $dateTimeHelper;

    /**
     * @var StdLibTimezoneInterface
     */
    private $timezone;

    /**
     * @var StatusSource
     */
    private $statusSource;

    /**
     * @var UserAgentParser
     */
    private $userAgentParser;

    /**
     * SessionsProvider constructor.
     * @param CustomerSession $customerSession
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     * @param CustomerActiveSessionRepositoryInterface $customerActiveSessionRepository
     * @param CustomerActiveSessionIteratorInterfaceFactory $customerActiveSessionsIteratorFactory
     * @param DateTimeHelper $dateTimeHelper
     * @param StdLibTimezoneInterface $timezone
     * @param StatusSource $statusSource
     * @param UserAgentParser $userAgentParser
     */
    public function __construct(
        CustomerSession $customerSession,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        CustomerActiveSessionRepositoryInterface $customerActiveSessionRepository,
        CustomerActiveSessionIteratorInterfaceFactory $customerActiveSessionsIteratorFactory,
        DateTimeHelper $dateTimeHelper,
        StdLibTimezoneInterface $timezone,
        StatusSource $statusSource,
        UserAgentParser $userAgentParser
    ) {
        $this->customerSession = $customerSession;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->customerActiveSessionRepository = $customerActiveSessionRepository;
        $this->customerActiveSessionsIteratorFactory = $customerActiveSessionsIteratorFactory;
        $this->dateTimeHelper = $dateTimeHelper;
        $this->timezone = $timezone;
        $this->statusSource = $statusSource;
        $this->userAgentParser = $userAgentParser;
    }

    /**
     * @return CustomerActiveSessionIteratorInterface
     */
    public function getCustomerActiveSessions(): CustomerActiveSessionIteratorInterface
    {
        if (null === $this->customerActiveSessions) {
            $resultItems = [];
            if ($this->customerSession->isLoggedIn()) {
                $result = $this->customerActiveSessionRepository->getList(
                    $this->generateSearchCriteria((int)$this->customerSession->getCustomerId())
                );
                $resultItems = $result->getItems();
            }

            $this->customerActiveSessions = $this->customerActiveSessionsIteratorFactory->create(
                ['data' => $resultItems]
            );
        }

        return $this->customerActiveSessions;
    }

    /**
     * @param string $date
     * @param string|null $format
     * @return string
     */
    public function formatDate(string $date, string $format = null): string
    {
        if (!$date) {
            return '';
        }

        if (null === $format) {
            $format = 'jS F Y \a\t h:ia';
        }

        return $this->dateTimeHelper->getDateTimeFormatted(
            $format,
            $date,
            $this->timezone->getConfigTimezone()
        );
    }

    /**
     * @param int $status
     * @return string
     */
    public function getStatusLabel(int $status): string
    {
        $statuses = $this->statusSource->toOptionHash();

        return (string)($statuses[$status] ?? '');
    }

    /**
     * @param string $userAgent
     * @param bool $withVersion
     * @return string
     */
    public function getBrowser(string $userAgent, bool $withVersion = false): string
    {
        $return = $this->userAgentParser->getBrowser($userAgent);
        $version = $withVersion
            ? $this->userAgentParser->getBrowserVersion($userAgent, true)
            : null;
        if ($return && $version) {
            $return .= ' ' . $version;
        }

        return $return;
    }

    /**
     * @param string $userAgent
     * @param bool $withVersion
     * @return string
     */
    public function getPlatform(string $userAgent, bool $withVersion = false): string
    {
        $return = $this->userAgentParser->getPlatform($userAgent);
        $version = $withVersion
            ? $this->userAgentParser->getPlatformVersion($userAgent, true)
            : null;
        if ($return && $version) {
            $return .= ' ' . $version;
        }

        return $return;
    }

    /**
     * @param int $customerId
     * @return SearchCriteriaInterface
     */
    public function generateSearchCriteria(int $customerId): SearchCriteriaInterface
    {
        $this->searchCriteriaBuilder->addFilter(
            CustomerActiveSessionInterface::CUSTOMER_ID,
            $customerId,
            'eq'
        );

        $this->sortOrderBuilder->setField(CustomerActiveSessionInterface::LAST_ACTIVITY_TIME);
        $this->sortOrderBuilder->setDescendingDirection();
        $this->searchCriteriaBuilder->addSortOrder(
            $this->sortOrderBuilder->create()
        );

        return $this->searchCriteriaBuilder->create();
    }
}
