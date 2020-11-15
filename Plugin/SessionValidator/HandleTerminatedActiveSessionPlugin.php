<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Plugin\SessionValidator;

use Dajve\CustomerActiveSessions\Model\ResourceModel\CustomerActiveSession as CustomerActiveSessionResource;
use Dajve\CustomerActiveSessions\Model\Source\CustomerActiveSession\Status as StatusSource;
use Magento\Framework\Exception\SessionException;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\Session\Validator as SessionValidator;

/**
 * Class HandleTerminatedActiveSessionPlugin
 * @package Dajve\CustomerActiveSessions\Plugin\SessionValidator
 * @author Dajve Green <me@dajve.co.uk>
 */
class HandleTerminatedActiveSessionPlugin
{
    /**
     * @var CustomerActiveSessionResource
     */
    private $customerActiveSessionResource;

    /**
     * HandleTerminatedActiveSessionPlugin constructor.
     * @param CustomerActiveSessionResource $customerActiveSessionResource
     */
    public function __construct(
        CustomerActiveSessionResource $customerActiveSessionResource
    ) {
        $this->customerActiveSessionResource = $customerActiveSessionResource;
    }

    /**
     * @param SessionValidator $subject
     * @param null $result
     * @param SessionManagerInterface $session
     * @return null
     * @throws SessionException
     */
    public function afterValidate(
        SessionValidator $subject,
        $result,
        SessionManagerInterface $session
    ) {
        $sessionId = $session->getSessionId();
        if ($sessionId
            && $this->customerActiveSessionResource->sessionIdExists($sessionId, StatusSource::GROUP_TERMINATED)) {
            $session->destroy(['clear_storage' => false]);
            
            throw new SessionException(__('The session has been terminated externally.'));
        }

        return $result;
    }
}
