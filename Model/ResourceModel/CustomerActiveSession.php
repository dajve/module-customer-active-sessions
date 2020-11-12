<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Model\ResourceModel;

use Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;
use Dajve\CustomerActiveSessions\CommonVars;
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
}
