<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Helper;

/**
 * Class GetterSetterHelper
 * @package Dajve\CustomerActiveSessions\Helper
 * @author Dajve Green <me@dajve.co.uk>
 */
class GetterSetterHelper
{
    /**
     * @var string
     */
    private $targetFQCN;

    /**
     * GetterSetterHelper constructor.
     * @param string $targetFQCN
     */
    public function __construct(string $targetFQCN)
    {
        $this->targetFQCN = $targetFQCN;
    }

    /**
     * @param object $object
     * @param string $field
     * @param mixed $value
     * @throws \InvalidArgumentException
     * @throws \BadMethodCallException
     */
    public function setData(object $object, string $field, $value): void
    {
        if (!($object instanceof $this->targetFQCN)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected object argument to be instance of "%s"; Received "%s"',
                $this->targetFQCN,
                get_class($object)
            ));
        }

        $method = $this->methodizeFieldName('set', $field);
        if (!method_exists($object, $method)) {
            throw new \BadMethodCallException(sprintf(
                'Method "%s" does not exist in object of type %s"',
                $method,
                get_class($object)
            ));
        }

        $object->{$method}($value);
    }

    /**
     * @param object $object
     * @param string $field
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws \BadMethodCallException
     */
    public function getData(object $object, string $field)
    {
        if (!($object instanceof $this->targetFQCN)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected object argument to be instance of "%s"; Received "%s"',
                $this->targetFQCN,
                get_class($object)
            ));
        }

        $method = $this->methodizeFieldName('get', $field);
        if (!method_exists($object, $method)) {
            throw new \BadMethodCallException(sprintf(
                'Method "%s" does not exist in object of type %s"',
                $method,
                get_class($object)
            ));
        }

        return $object->{$method}();
    }

    /**
     * @param string $prefix
     * @param string $field
     * @return string
     */
    private function methodizeFieldName(string $prefix, string $field): string
    {
        return $prefix . str_replace('_', '', ucwords($field, '_'));
    }
}
