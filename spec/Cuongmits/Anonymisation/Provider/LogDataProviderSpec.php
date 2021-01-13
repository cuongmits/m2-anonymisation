<?php

namespace spec\Cuongmits\Anonymisation\Provider;

use PhpSpec\ObjectBehavior;
use Cuongmits\Anonymisation\Provider\LogDataProvider;
use Magento\Sales\Model\Order;

class LogDataProviderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(LogDataProvider::class);
    }

    function it_should_return_correct_order_data(Order $order)
    {
        $data = ['value'];
        $order->getData()->willReturn($data);

        $this->getLogDataFromOrder($order)->shouldReturn('["value"]');
    }
}
