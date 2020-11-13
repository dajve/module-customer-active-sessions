<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Model\CustomerActiveSession;

use Dajve\CustomerActiveSessions\Api\Data\CustomerActiveSessionInterface;
use Dajve\CustomerActiveSessions\Helper\GetterSetterHelper;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Validator\AbstractValidator;

/**
 * Class Validator
 * @package Dajve\CustomerActiveSessions\Model
 * @author Dajve Green <me@dajve.co.uk>
 */
class Validator extends AbstractValidator
{
    /**
     * @var OptionSourceInterface
     */
    private $statusSource;

    /**
     * @var GetterSetterHelper
     */
    private $getterSetterHelper;

    /**
     * Validator constructor.
     * @param OptionSourceInterface $statusSource
     * @param GetterSetterHelper $getterSetterHelper
     */
    public function __construct(
        OptionSourceInterface $statusSource,
        GetterSetterHelper $getterSetterHelper
    ) {
        $this->statusSource = $statusSource;
        $this->getterSetterHelper = $getterSetterHelper;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        if (!($value instanceof CustomerActiveSessionInterface)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid "value" argument for validator. Expected "%s"; encountered "%s"',
                CustomerActiveSessionInterface::class,
                // phpcs:ignore Magento2.Functions.DiscouragedFunction.Discouraged
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }

        $return = true;

        $requiredFields = [
            CustomerActiveSessionInterface::SESSION_ID,
            CustomerActiveSessionInterface::CUSTOMER_ID,
            CustomerActiveSessionInterface::STATUS,
        ];
        foreach ($requiredFields as $requiredField) {
            $return = $this->validateRequiredField($value, $requiredField) && $return;
        }

        $dateFields = [
            CustomerActiveSessionInterface::START_TIME,
            CustomerActiveSessionInterface::LAST_ACTIVITY_TIME,
            CustomerActiveSessionInterface::TERMINATION_TIME,
        ];
        foreach ($dateFields as $dateField) {
            $return = $this->validateIsValidDateField($value, $dateField) && $return;
        }

        $return = $this->validateIsValidStatus($value) && $return;

        return $return;
    }

    /**
     * @param CustomerActiveSessionInterface $customerActiveSession
     * @param string $field
     * @return bool
     */
    public function validateRequiredField(
        CustomerActiveSessionInterface $customerActiveSession,
        string $field
    ): bool {
        try {
            $value = $this->getterSetterHelper->getData($customerActiveSession, $field);
        } catch (\BadMethodCallException $e) {
            $this->_addMessages([
                __('Active session data missing for "%s"', $field)
            ]);

            return false;
        }

        if (is_string($value)) {
            $value = trim($value);
        }
        if (!$value) {
            $this->_addMessages([
                __('Active session data missing for "%s"', $field)
            ]);

            return false;
        }

        return true;
    }

    /**
     * @param CustomerActiveSessionInterface $customerActiveSession
     * @param string $field
     * @return bool
     */
    public function validateIsValidDateField(
        CustomerActiveSessionInterface $customerActiveSession,
        string $field
    ): bool {
        try {
            $value = $this->getterSetterHelper->getData($customerActiveSession, $field);
        } catch (\BadMethodCallException $e) {
            return true;
        }

        if (is_string($value)) {
            $value = trim($value);
        }
        if (!$value) {
            return true;
        }

        if (!is_string($value)) {
            $this->_addMessages([
                __(
                    'Invalid value type for active session data date field "%1". Expected string; received "%2"',
                    $field,
                    // phpcs:ignore Magento2.Functions.DiscouragedFunction.Discouraged
                    is_object($value) ? get_class($value) : gettype($value)
                )
            ]);

            return false;
        }

        $strtotime = strtotime($value);
        if (false === $strtotime) {
            $this->_addMessages([
                __('Invalid value for active session data date field "%1" (%2)', $field, $value)
            ]);

            return false;
        }

        return true;
    }

    /**
     * @param CustomerActiveSessionInterface $customerActiveSession
     * @return bool
     */
    public function validateIsValidStatus(CustomerActiveSessionInterface $customerActiveSession): bool
    {
        $status = $customerActiveSession->getStatus();
        if (!$status) {
            return false;
        }

        $validStatuses = array_column($this->statusSource->toOptionArray(), 'value');
        if (!in_array($status, $validStatuses, true)) {
            $this->_addMessages([
                __(
                    'Status "%1" is not in list of permitted values for active session data: %2',
                    $status,
                    implode(',', $validStatuses)
                )
            ]);

            return false;
        }

        return true;
    }
}
