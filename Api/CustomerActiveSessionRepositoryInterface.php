<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Api;

use Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Interface CustomerActiveSessionRepositoryInterface
 * @package Dajve\CustomerActiveSessions\Api
 * @author Dajve Green <me@dajve.co.uk>
 */
interface CustomerActiveSessionRepositoryInterface
{
    /**
     * @param int $id
     * @return CustomerActiveSessionInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): CustomerActiveSessionInterface;

    /**
     * @param int $sessionId
     * @return CustomerActiveSessionInterface
     * @throws NoSuchEntityException
     */
    public function getBySessionId(string $sessionId): CustomerActiveSessionInterface;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    /**
     * @param CustomerActiveSessionInterface $customerActiveSession
     * @return CustomerActiveSessionInterface
     * @throws CouldNotSaveException
     * @throws AlreadyExistsException
     */
    public function save(CustomerActiveSessionInterface $customerActiveSession): CustomerActiveSessionInterface;

    /**
     * @param int $id
     * @return bool
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $id): bool;

    /**
     * @param CustomerActiveSessionInterface $customerActiveSession
     * @return bool
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function delete(CustomerActiveSessionInterface $customerActiveSession): bool;
}
