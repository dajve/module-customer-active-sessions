<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Model;

use Dajve\CustomerActiveSessions\Api\CustomerActiveSessionRepositoryInterface;
use Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;
use Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterfaceFactory;
use Dajve\CustomerActiveSessions\Model\ResourceModel\CustomerActiveSession as CustomerActiveSessionResource;
use Dajve\CustomerActiveSessions\Model\ResourceModel\CustomerActiveSession\CollectionFactory as CustomerActiveSessionCollectionFactory; // phpcs:ignore Magento2.Files.LineLength.MaxExceeded
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

/**
 * Class CustomerActiveSessionRepository
 * @package Dajve\CustomerActiveSessions\Model
 * @author Dajve Green <me@dajve.co.uk>
 */
class CustomerActiveSessionRepository implements CustomerActiveSessionRepositoryInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var CustomerActiveSessionResource
     */
    private $resource;

    /**
     * @var CustomerActiveSessionInterfaceFactory
     */
    private $customerActiveSessionFactory;

    /**
     * @var CustomerActiveSessionCollectionFactory
     */
    private $customerActiveSessionCollectionFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var SearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * CustomerActiveSessionRepository constructor.
     * @param LoggerInterface $logger
     * @param CustomerActiveSessionResource $resource
     * @param CustomerActiveSessionInterfaceFactory $customerActiveSessionFactory
     * @param CustomerActiveSessionCollectionFactory $customerActiveSessionCollectionFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        LoggerInterface $logger,
        CustomerActiveSessionResource $resource,
        CustomerActiveSessionInterfaceFactory $customerActiveSessionFactory,
        CustomerActiveSessionCollectionFactory $customerActiveSessionCollectionFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->logger = $logger;
        $this->resource = $resource;
        $this->customerActiveSessionFactory = $customerActiveSessionFactory;
        $this->customerActiveSessionCollectionFactory = $customerActiveSessionCollectionFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @param int $id
     * @return CustomerActiveSessionInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): CustomerActiveSessionInterface
    {
        return $this->getByField(CustomerActiveSessionInterface::ID, $id);
    }

    /**
     * @param int $sessionId
     * @return CustomerActiveSessionInterface
     * @throws NoSuchEntityException
     */
    public function getBySessionId(int $sessionId): CustomerActiveSessionInterface
    {
        return $this->getByField(CustomerActiveSessionInterface::SESSION_ID, $sessionId);
    }

    /**
     * @param string $field
     * @param $value
     * @return CustomerActiveSessionInterface
     * @throws NoSuchEntityException
     */
    private function getByField(string $field, $value): CustomerActiveSessionInterface
    {
        $customerActiveSession = $this->customerActiveSessionFactory->create();
        $this->resource->load($customerActiveSession, $value, $field);

        if (!$customerActiveSession->getId()) {
            throw NoSuchEntityException::singleField($field, $value);
        }

        return $customerActiveSession;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        $collection = $this->customerActiveSessionCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * @param CustomerActiveSessionInterface $customerActiveSession
     * @return CustomerActiveSessionInterface
     * @throws CouldNotSaveException
     */
    public function save(CustomerActiveSessionInterface $customerActiveSession): CustomerActiveSessionInterface
    {
        try {
            $this->resource->save($customerActiveSession);
        } catch (LocalizedException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['originalException' => $e]);
            throw new CouldNotSaveException(__('An error occurred while saving the active session data'));
        }

        return $customerActiveSession;
    }

    /**
     * @param int $id
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $id): bool
    {
        return $this->delete($this->getById($id));
    }

    /**
     * @param CustomerActiveSessionInterface $customerActiveSession
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(CustomerActiveSessionInterface $customerActiveSession): bool
    {
        try {
            $this->resource->delete($customerActiveSession);
        } catch (LocalizedException $e) {
            throw new CouldNotDeleteException(__($e->getMessage()));
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['originalException' => $e]);
            throw new CouldNotDeleteException(__('An error occurred while deleting the active session data'));
        }

        return true;
    }
}
