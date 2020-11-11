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
}
