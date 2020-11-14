<?php

declare(strict_types=1);

namespace Dajve\CustomerActiveSessions\Helper;

/**
 * Class UserAgentParser
 * @package Dajve\CustomerActiveSessions\Helper
 * @author Dajve Green <me@dajve.co.uk>
 */
class UserAgentParser
{
    /**
     * @var array[]
     */
    private $userAgentParsed = [];

    /**
     * @param string $userAgent
     * @return string
     */
    public function getBrowser(string $userAgent): string
    {
        $userAgentParsed = $this->getUserAgentParsed($userAgent);

        return $userAgentParsed['browser'] ?? '';
    }

    /**
     * @param string $userAgent
     * @param bool $majorOnly
     * @return string
     */
    public function getBrowserVersion(string $userAgent, bool $majorOnly = false): string
    {
        $userAgentParsed = $this->getUserAgentParsed($userAgent);

        $return = $userAgentParsed['browser_version'] ?? '';
        if ($majorOnly) {
            $return = preg_replace('/\..*$/', '', $return);
        }

        return $return;
    }

    /**
     * @param string $userAgent
     * @return string
     */
    public function getPlatform(string $userAgent): string
    {
        $userAgentParsed = $this->getUserAgentParsed($userAgent);

        return $userAgentParsed['platform'] ?? '';
    }

    /**
     * @param string $userAgent
     * @return string
     */
    public function getPlatformVersion(string $userAgent, bool $majorOnly = false): string
    {
        $userAgentParsed = $this->getUserAgentParsed($userAgent);

        $return = $userAgentParsed['platform_version'] ?? '';
        if ($majorOnly) {
            $return = preg_replace('/\..*$/', '', $return);
        }

        return $return;
    }

    /**
     * @param string $userAgent
     * @return string
     */
    public function getFullPlatformString(string $userAgent): string
    {
        $return = $this->getPlatform($userAgent);
        $version = $this->getPlatformVersion($userAgent);
        if ($return && $version) {
            $return .= ' '. $version;
        }

        return $return;
    }

    /**
     * @param string $userAgent
     * @return array
     */
    public function getUserAgentParsed(string $userAgent): array
    {
        if (!isset($this->userAgentParsed[$userAgent])) {
            $userAgentParsed = [];
            if (function_exists('parse_user_agent')) {
                $userAgentParsed = parse_user_agent($userAgent);
            }

            $this->userAgentParsed[$userAgent] = [
                'browser' => $userAgentParsed['browser'] ?? '',
                'browser_version' => $userAgentParsed['version'] ?? '',
                'platform' => $userAgentParsed['platform'] ?? '',
                'platform_version' => '', // For future compatibility should implementations switch out parser module
            ];
        }

        return $this->userAgentParsed[$userAgent];
    }
}
