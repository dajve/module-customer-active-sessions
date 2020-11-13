<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Model\ResourceModel;

use Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;
use Dajve\CustomerActiveSessions\CommonVars;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class CustomerActiveSession
 * @package Dajve\CustomerActiveSessions\Model\ResourceModel
 * @author Dajve Green <me@dajve.co.uk>
 */
class CustomerActiveSession extends AbstractDb
{
    /**
     * {@inheritdoc}
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            CommonVars::DB_TABLE_CUSTOMER_ACTIVE_SESSION,
            CustomerActiveSessionInterface::ID
        );
    }

    /**
     * @param string $sessionId
     * @return bool
     */
    public function sessionIdExists(string $sessionId): bool
    {
        $connection = $this->getConnection();

        $select = $connection->select();
        $select->from(CommonVars::DB_TABLE_CUSTOMER_ACTIVE_SESSION, [
            CustomerActiveSessionInterface::ID
        ]);
        $select->where(
            sprintf('`%s` = ?', CustomerActiveSessionInterface::SESSION_ID),
            $sessionId
        );
        $select->limit(1);

        return (bool)$connection->fetchOne($select);
    }

    /**
     * @param string $sessionId
     * @param int $customerId
     * @param array $data
     * @return bool
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     * @throws \LogicException
     */
    public function updateBySessionAndCustomerId(
        string $sessionId,
        int $customerId,
        array $data
    ): bool {
        $connection = $this->getConnection();

        try {
            $affectedRows = $connection->update(
                CommonVars::DB_TABLE_CUSTOMER_ACTIVE_SESSION,
                $data,
                [
                    $connection->quoteInto(
                        sprintf('`%s` = ?', CustomerActiveSessionInterface::SESSION_ID),
                        $sessionId
                    ),
                    $connection->quoteInto(
                        sprintf('`%s` = ?', CustomerActiveSessionInterface::CUSTOMER_ID),
                        $customerId
                    ),
                ]
            );

            // phpcs:disable Squiz.PHP.NonExecutableCode.Unreachable
            switch ($affectedRows) {
                case 1:
                    // Expected result
                    break;

                case 0:
                    throw NoSuchEntityException::doubleField(
                        CustomerActiveSessionInterface::SESSION_ID,
                        $sessionId,
                        CustomerActiveSessionInterface::CUSTOMER_ID,
                        $customerId
                    );
                    /** @noinspection PhpUnreachableStatementInspection */
                    break;

                default:
                    throw new \LogicException(sprintf(
                        'Multiple (%s) active session rows updated for customer_id: "%s", session_id: "%s"',
                        $affectedRows,
                        $customerId,
                        $sessionId
                    ));
                    /** @noinspection PhpUnreachableStatementInspection */
                    break;
            }
            // phpcs:enable Squiz.PHP.NonExecutableCode.Unreachable
        } catch (\Zend_Db_Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()), $e);
        }

        return true;
    }
}
