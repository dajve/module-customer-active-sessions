<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Model\ResourceModel\CustomerActiveSession;

use Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;
use Dajve\CustomerActiveSessions\Model\CustomerActiveSession;
use Dajve\CustomerActiveSessions\Model\ResourceModel\CustomerActiveSession as CustomerActiveSessionResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Dajve\CustomerActiveSessions\Model\ResourceModel
 * @author Dajve Green <me@dajve.co.uk>
 */
class Collection extends AbstractCollection
{
    /**
     * {@inheritdoc}
     * @var string
     */
    protected $_idFieldName = CustomerActiveSessionInterface::ID;

    /**
     * {@inheritdoc}
     * @var string
     */
    protected $_eventPrefix = 'dajve_customeractivesessions_customeractivesession_collection';

    /**
     * {@inheritdoc}
     * @var string
     */
    protected $_eventObject = 'customeractivesession_collection';

    /**
     * {@inheritdoc}
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->_init(
            CustomerActiveSession::class,
            CustomerActiveSessionResource::class
        );
    }
}
