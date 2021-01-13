<?php

namespace spec\Cuongmits\Anonymisation\Provider;

use Prophecy\Argument;
use Cuongmits\Anonymisation\AnonymisedValues;
use Cuongmits\Anonymisation\Provider\ConfigProvider;
use PhpSpec\ObjectBehavior;
use Cuongmits\Anonymisation\Provider\OrderProvider;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Zend_Db_Statement_Interface;

class OrderProviderSpec extends ObjectBehavior
{
    function let(ConfigProvider $configProvider, ResourceConnection $resourceConnection, AdapterInterface $connection)
    {
        $resourceConnection->getConnection()->willReturn($connection);

        $this->beConstructedWith($configProvider, $resourceConnection);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(OrderProvider::class);
    }

    function it_should_return_correct_unconnected_quote_ids(
        ConfigProvider $configProvider,
        AdapterInterface $connection,
        Zend_Db_Statement_Interface $res
    ) {
        $configProvider->getOtherRetentionTime()->willReturn(180);
        $connection->query(
            Argument::any(),
            [OrderProvider::CANCELLED_ORDER_STATE, 180, AnonymisedValues::DEFAULT_ANONYMISED_EMAIL]
        )->willReturn($res);
        $res->fetchAll()->willReturn([
            ['entity_id' => '1001'],
            ['entity_id' => '1002']
        ]);

        $this->getCancelledOrderIds()->shouldReturn(['1001', '1002']);
    }

    function it_should_return_correct_connected_quote_ids(
        ConfigProvider $configProvider,
        AdapterInterface $connection,
        Zend_Db_Statement_Interface $res
    ) {
        $configProvider->getGeneralRetentionTime()->willReturn(180);
        $connection->query(
            Argument::any(),
            [OrderProvider::CANCELLED_ORDER_STATE, 180, AnonymisedValues::DEFAULT_ANONYMISED_EMAIL]
        )->willReturn($res);
        $res->fetchAll()->willReturn([
            ['entity_id' => '1001'],
            ['entity_id' => '1002']
        ]);

        $this->getNonCancelledOrderIds()->shouldReturn(['1001', '1002']);
    }
}
