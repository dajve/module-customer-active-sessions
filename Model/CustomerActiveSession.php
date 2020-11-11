<?php
/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
// phpcs:disable Magento2.Files.LineLength.MaxExceeded

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Model;

use Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionExtensionInterface;
use Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionExtensionInterfaceFactory;
use Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;
use Dajve\CustomerActiveSessions\Model\ResourceModel\CustomerActiveSession as CustomerActiveSessionResource;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Validator\ValidatorInterface;

/**
 * Class CustomerActiveSession
 * @package Dajve\CustomerActiveSessions\Model
 * @author Dajve Green <me@dajve.co.uk>
 */
class CustomerActiveSession extends AbstractModel implements CustomerActiveSessionInterface
{
    /**
     * {@inheritdoc}
     * @var string
     */
    protected $_eventPrefix = 'dajve_customeractivesessions_customeractivesession';

    /**
     * {@inheritdoc}
     * @var string
     */
    protected $_eventObject = 'customeractivesession';

    /**
     * @var CustomerActiveSessionExtensionInterfaceFactory
     */
    private $customerActiveSessionExtensionFactory;

    /**
     * CustomerActiveSession constructor.
     * @param Context $context
     * @param Registry $registry
     * @param CustomerActiveSessionExtensionInterfaceFactory $customerActiveSessionExtensionFactory
     * @param ValidatorInterface $validator
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        CustomerActiveSessionExtensionInterfaceFactory $customerActiveSessionExtensionFactory,
        ValidatorInterface $validator,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);

        $this->customerActiveSessionExtensionFactory = $customerActiveSessionExtensionFactory;
        $this->_validatorBeforeSave = $validator;
    }

    /**
     * {@inheritdoc}
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->_init(CustomerActiveSessionResource::class);
    }

    /**
     * @param string $sessionId
     * @return CustomerActiveSessionInterface
     */
    public function setSessionId(string $sessionId): \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface
    {
        return $this->setData(static::SESSION_ID, trim($sessionId));
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return (string)$this->getData(static::SESSION_ID);
    }

    /**
     * @param int $customerId
     * @return CustomerActiveSessionInterface
     */
    public function setCustomerId(int $customerId): \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface
    {
        return $this->setData(static::CUSTOMER_ID, $customerId);
    }

    /**
     * @return int
     */
    public function getCustomerId(): int
    {
        return (int)$this->getData(static::CUSTOMER_ID);
    }

    /**
     * @param string $startTime
     * @return CustomerActiveSessionInterface
     */
    public function setStartTime(string $startTime): \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface
    {
        return $this->setData(static::START_TIME, trim($startTime));
    }

    /**
     * @return string
     */
    public function getStartTime(): string
    {
        return (string)$this->getData(static::START_TIME);
    }

    /**
     * @param string $lastActivityTime
     * @return CustomerActiveSessionInterface
     */
    public function setLastActivityTime(string $lastActivityTime): \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface
    {
        return $this->setData(static::LAST_ACTIVITY_TIME, trim($lastActivityTime));
    }

    /**
     * @return string
     */
    public function getLastActivityTime(): string
    {
        return (string)$this->getData(static::LAST_ACTIVITY_TIME);
    }

    /**
     * @param string $terminationTime
     * @return CustomerActiveSessionInterface
     */
    public function setTerminationTime(string $terminationTime): \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface
    {
        return $this->setData(static::TERMINATION_TIME, trim($terminationTime));
    }

    /**
     * @return string|null
     */
    public function getTerminationTime(): ?string
    {
        $terminationTime = $this->getData(static::TERMINATION_TIME);
        if (is_string($terminationTime) && !trim($terminationTime)) {
            $terminationTime = null;
        }

        return $terminationTime;
    }

    /**
     * @param int $initialStoreId
     * @return CustomerActiveSessionInterface
     */
    public function setInitialStoreId(int $initialStoreId): \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface
    {
        return $this->setData(static::INITIAL_STORE_ID, $initialStoreId);
    }

    /**
     * @return int
     */
    public function getInitialStoreId(): int
    {
        return (int)$this->getData(static::INITIAL_STORE_ID);
    }

    /**
     * @param int $lastStoreId
     * @return CustomerActiveSessionInterface
     */
    public function setLastStoreId(int $lastStoreId): \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface
    {
        return $this->setData(static::LAST_STORE_ID, $lastStoreId);
    }

    /**
     * @return int
     */
    public function getLastStoreId(): int
    {
        return (int)$this->getData(static::LAST_STORE_ID);
    }

    /**
     * @param int $status
     * @return CustomerActiveSessionInterface
     */
    public function setStatus(int $status): \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface
    {
        return $this->setData(static::STATUS, $status);
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return (int)$this->getStatus();
    }

    /**
     * @param string $remoteIp
     * @return CustomerActiveSessionInterface
     */
    public function setRemoteIp(string $remoteIp): \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface
    {
        return $this->setData(static::REMOTE_IP, trim($remoteIp));
    }

    /**
     * @return string
     */
    public function getRemoteIp(): string
    {
        return (string)$this->getData(static::REMOTE_IP);
    }

    /**
     * @param string $userAgent
     * @return CustomerActiveSessionInterface
     */
    public function setUserAgent(string $userAgent): \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface
    {
        return $this->setData(static::USER_AGENT, trim($userAgent));
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return (string)$this->getData(static::USER_AGENT);
    }

    /**
     * @param CustomerActiveSessionExtensionInterface $extensionAttributes
     * @return CustomerActiveSessionInterface
     */
    public function setExtensionAttributes(CustomerActiveSessionExtensionInterface $extensionAttributes): \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface
    {
        return $this->setData(static::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }

    /**
     * @return CustomerActiveSessionExtensionInterface
     */
    public function getExtensionAttributes(): \Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionExtensionInterface
    {
        $extensionAttributes = $this->getData(static::EXTENSION_ATTRIBUTES_KEY);
        if (null === $extensionAttributes) {
            $extensionAttributes = $this->customerActiveSessionExtensionFactory->create();
            $this->setExtensionAttributes($extensionAttributes);
        }

        return $extensionAttributes;
    }
}
