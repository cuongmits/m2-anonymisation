<?php

namespace spec\Cuongmits\Anonymisation\Provider;

use Prophecy\Argument;
use Cuongmits\Anonymisation\AnonymisedValues;
use Cuongmits\Anonymisation\Provider\ConfigProvider;
use PhpSpec\ObjectBehavior;
use Cuongmits\Anonymisation\Provider\QuoteProvider;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Zend_Db_Statement_Interface;

class QuoteProviderSpec extends ObjectBehavior
{
    function let(ConfigProvider $configProvider, ResourceConnection $resourceConnection, AdapterInterface $connection)
    {
        $resourceConnection->getConnection()->willReturn($connection);

        $this->beConstructedWith($configProvider, $resourceConnection);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(QuoteProvider::class);
    }

    function it_should_return_correct_unconnected_quote_ids(
        ConfigProvider $configProvider,
        AdapterInterface $connection,
        Zend_Db_Statement_Interface $res
    ) {
        $configProvider->getOtherRetentionTime()->willReturn(180);
        $configProvider->getCookieLifetime()->willReturn(3600);
        $connection->query(Argument::any(), [180, 3600, AnonymisedValues::DEFAULT_ANONYMISED_EMAIL])->willReturn($res);
        $res->fetchAll()->willReturn([
            ['entity_id' => '1001'],
            ['entity_id' => '1002']
        ]);

        $this->getUnconnectedQuoteIds()->shouldReturn(['1001', '1002']);
    }

    function it_should_return_correct_connected_quote_ids(
        ConfigProvider $configProvider,
        AdapterInterface $connection,
        Zend_Db_Statement_Interface $res
    ) {
        $configProvider->getGeneralRetentionTime()->willReturn(180);
        $configProvider->getCookieLifetime()->willReturn(3600);
        $connection->query(Argument::any(), [180, 3600, AnonymisedValues::DEFAULT_ANONYMISED_EMAIL])->willReturn($res);
        $res->fetchAll()->willReturn([
            ['entity_id' => '1001'],
            ['entity_id' => '1002']
        ]);

        $this->getConnectedQuoteIds()->shouldReturn(['1001', '1002']);
    }
}
