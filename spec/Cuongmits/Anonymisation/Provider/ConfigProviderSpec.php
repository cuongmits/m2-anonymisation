<?php

namespace spec\Cuongmits\Anonymisation\Provider;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Cuongmits\Anonymisation\Provider\ConfigProvider;
use PhpSpec\ObjectBehavior;
use Cuongmits\FeatureFlag\FeatureFlag;
use Magento\Framework\Session\Config;
use Magento\Store\Model\ScopeInterface;

class ConfigProviderSpec extends ObjectBehavior
{
    function let(ScopeConfigInterface $scopeConfig, FeatureFlag $featureFlag)
    {
        $this->beConstructedWith($scopeConfig, $featureFlag);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ConfigProvider::class);
    }

    function it_should_return_default_number_when_general_time_is_not_set_on_admin(ScopeConfigInterface $scopeConfig)
    {
        $scopeConfig->getValue(ConfigProvider::ANONYMISATION_GENERAL_TIME)->willReturn(null);

        $this->getGeneralRetentionTime()->shouldReturn(ConfigProvider::DEFAULT_TIME_TO_STORE_GUEST_DATA);
    }

    function it_should_return_general_time_set(ScopeConfigInterface $scopeConfig)
    {
        $scopeConfig->getValue(ConfigProvider::ANONYMISATION_GENERAL_TIME)->willReturn(365);

        $this->getGeneralRetentionTime()->shouldReturn(365);
    }

    function it_should_return_default_number_when_cancelled_orders_time_is_not_set_on_admin(ScopeConfigInterface $scopeConfig)
    {
        $scopeConfig->getValue(ConfigProvider::ANONYMISATION_OTHER_TIME)->willReturn(null);

        $this->getOtherRetentionTime()->shouldReturn(ConfigProvider::DEFAULT_TIME_TO_STORE_GUEST_DATA);
    }

    function it_should_return_cancelled_orders_time_set(ScopeConfigInterface $scopeConfig)
    {
        $scopeConfig->getValue(ConfigProvider::ANONYMISATION_OTHER_TIME)->willReturn(365);

        $this->getOtherRetentionTime()->shouldReturn(365);
    }

    function it_should_return_correct_feature_flag_value(FeatureFlag $featureFlag)
    {
        $featureFlag->isEnabled(FeatureFlag::GUEST_DATA_ANONYMISATION)->willReturn(true);

        $this->isAnonymisationEnabled()->shouldReturn(true);

        $featureFlag->isEnabled(FeatureFlag::GUEST_DATA_ANONYMISATION)->willReturn(false);

        $this->isAnonymisationEnabled()->shouldReturn(false);
    }

    function it_should_return_correct_cookie_lifetime(ScopeConfigInterface $scopeConfig)
    {
        $scopeConfig->getValue(Config::XML_PATH_COOKIE_LIFETIME, ScopeInterface::SCOPE_STORE)->willReturn(3600);

        $this->getCookieLifetime()->shouldReturn(3600);
    }
}
