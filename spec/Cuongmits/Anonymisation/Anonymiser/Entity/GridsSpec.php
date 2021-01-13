<?php

namespace spec\Cuongmits\Anonymisation\Anonymiser\Entity;

use Exception;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Cuongmits\Anonymisation\Anonymiser\Entity\BaseInterface;
use Cuongmits\Anonymisation\Anonymiser\Entity\Grid\OrderGrid;
use PhpSpec\ObjectBehavior;
use Cuongmits\Anonymisation\Anonymiser\Entity\Grids;
use Cuongmits\Anonymisation\Exception\AnonymisationException;
use Magento\Sales\Model\ResourceModel\GridPool;

final class GridsSpec extends ObjectBehavior
{
    function let(LoggerInterface $logger, GridPool $gridPool)
    {
        $this->beConstructedWith($logger, $gridPool);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Grids::class);
    }

    function it_is_instance_of_base_class()
    {
        $this->shouldBeAnInstanceOf(BaseInterface::class);
    }

    function it_should_process_all_order_related_grids_data(LoggerInterface $logger, GridPool $gridPool)
    {
        $id = 1001;
        $gridPool->refreshByOrderId($id)->shouldBeCalled();
        $logger->info(Argument::cetera())->shouldBeCalled();
        $logger->error(Argument::cetera())->shouldNotBeCalled();

        $this->process($id)->shouldReturn(1);
    }

    function it_should_throw_an_exception_when_something_is_wrong(LoggerInterface $logger, GridPool $gridPool)
    {
        $id = 1001;
        $gridPool->refreshByOrderId($id)->shouldBeCalled()->willThrow(Exception::class);
        $logger->info(Argument::cetera())->shouldNotBeCalled();
        $logger->error(Argument::type('string'), Argument::withKey('exception'))->shouldBeCalled();

        $this->shouldThrow(AnonymisationException::class)->duringProcess($id);
    }

    function it_should_return_correct_entity_name()
    {
        $this->getEntityName()->shouldReturn('Order-Related Grids');
    }
}
