<?php

namespace spec\Cuongmits\Anonymisation\Anonymiser;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Cuongmits\Anonymisation\Anonymiser\OtherAnonymiser;
use Cuongmits\Anonymisation\Provider\ConfigProvider;
use Cuongmits\Anonymisation\Provider\OrderProvider;
use Cuongmits\Anonymisation\Provider\QuoteProvider;
use Cuongmits\Anonymisation\Anonymiser\QuoteRelatedAnonymiser;
use Cuongmits\Anonymisation\Anonymiser\OrderRelatedAnonymiser;

class OtherAnonymiserSpec extends ObjectBehavior
{
    function let(
        ConfigProvider $configProvider,
        LoggerInterface $logger,
        QuoteProvider $quoteProvider,
        OrderProvider $orderProvider,
        QuoteRelatedAnonymiser $quoteRelatedAnonymiser,
        OrderRelatedAnonymiser $orderRelatedAnonymiser
    ) {
        $this->beConstructedWith(
            $configProvider,
            $logger,
            $quoteProvider,
            $orderProvider,
            $quoteRelatedAnonymiser,
            $orderRelatedAnonymiser
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(OtherAnonymiser::class);
    }

    function it_should_do_nothing_when_anonymisation_flag_is_disabled(
        ConfigProvider $configProvider,
        LoggerInterface $logger,
        QuoteProvider $quoteProvider,
        OrderProvider $orderProvider
    ) {
        $configProvider->isAnonymisationEnabled()->willReturn(false);

        $quoteProvider->getUnconnectedQuoteIds()->shouldNotBeCalled();
        $orderProvider->getCancelledOrderIds()->shouldNotBeCalled();

        $logger->info(Argument::cetera())->shouldBeCalled();

        $this->run()->shouldReturn(['quote' => 0, 'order' => 0]);
    }

    function it_should_remove_personal_data_from_incomplete_purchases(
        ConfigProvider $configProvider,
        LoggerInterface $logger,
        QuoteProvider $quoteProvider,
        OrderProvider $orderProvider,
        QuoteRelatedAnonymiser $quoteRelatedAnonymiser,
        OrderRelatedAnonymiser $orderRelatedAnonymiser
    ) {
        $configProvider->isAnonymisationEnabled()->willReturn(true);

        $logger->info(Argument::cetera())->shouldBeCalled();

        $quoteProvider->getUnconnectedQuoteIds()->willReturn([5, 7]);
        $quoteRelatedAnonymiser->process(5)->willReturn(1);
        $quoteRelatedAnonymiser->process(7)->willReturn(1);

        $orderProvider->getCancelledOrderIds()->willReturn([6]);
        $orderRelatedAnonymiser->process(6)->willReturn(1);

        $logger->info(Argument::cetera())->shouldBeCalled();

        $this->run()->shouldReturn(['quote' => 2, 'order' => 1]);
    }
}
