<?php

namespace Cuongmits\Anonymisation\Provider;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Session\Config;
use Magento\Store\Model\ScopeInterface;
use Cuongmits\FeatureFlag\FeatureFlag;

class ConfigProvider
{
    public const DEFAULT_TIME_TO_STORE_GUEST_DATA = 180;
    public const ANONYMISATION_GENERAL_TIME = 'toom_general/anonymise_guest_data/general_case_time';
    public const ANONYMISATION_OTHER_TIME = 'toom_general/anonymise_guest_data/other_case_time';

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /** @var FeatureFlag */
    private $featureFlag;

    public function __construct(ScopeConfigInterface $scopeConfig, FeatureFlag $featureFlag)
    {
        $this->scopeConfig = $scopeConfig;
        $this->featureFlag = $featureFlag;
    }

    public function getGeneralRetentionTime(): int
    {
        $time = $this->scopeConfig->getValue(self::ANONYMISATION_GENERAL_TIME);

        return $time ?? self::DEFAULT_TIME_TO_STORE_GUEST_DATA;
    }

    public function getOtherRetentionTime(): int
    {
        $time = $this->scopeConfig->getValue(self::ANONYMISATION_OTHER_TIME);

        return $time ?? self::DEFAULT_TIME_TO_STORE_GUEST_DATA;
    }

    public function getCookieLifetime(): int
    {
        return (int)$this->scopeConfig->getValue(Config::XML_PATH_COOKIE_LIFETIME, ScopeInterface::SCOPE_STORE);
    }

    public function isAnonymisationEnabled(): bool
    {
        return $this->featureFlag->isEnabled(FeatureFlag::GUEST_DATA_ANONYMISATION);
    }
}
