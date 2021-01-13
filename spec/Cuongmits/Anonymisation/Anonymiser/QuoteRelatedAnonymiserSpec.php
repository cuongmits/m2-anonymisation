<?php

namespace spec\Cuongmits\Anonymisation\Anonymiser;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Exception;
use Cuongmits\Anonymisation\Anonymiser\QuoteRelatedAnonymiser;
use Cuongmits\Anonymisation\Anonymiser\Entity\BaseInterface;

class QuoteRelatedAnonymiserSpec extends ObjectBehavior
{
    function let(
        ResourceConnection $resourceConnection,
        LoggerInterface $logger,
        BaseInterface $entityAnonymiser1,
        BaseInterface $entityAnonymiser2,
        AdapterInterface $connection
    ) {
        $resourceConnection->getConnection()->willReturn($connection);

        $this->beConstructedWith(
            $resourceConnection,
            $logger,
            [$entityAnonymiser1, $entityAnonymiser2]
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(QuoteRelatedAnonymiser::class);
    }

    function it_should_run_anonymisation_process_for_every_quote_related_anonymisers(
        BaseInterface $entityAnonymiser1,
        BaseInterface $entityAnonymiser2,
        AdapterInterface $connection,
        LoggerInterface $logger
    ) {
        $id = 100;

        $connection->beginTransaction()->shouldBeCalled();

        $entityAnonymiser1->getEntityName()->shouldBeCalled()->willReturn('name1');
        $entityAnonymiser1->process($id)->shouldBeCalled();
        $entityAnonymiser2->getEntityName()->shouldBeCalled()->willReturn('name2');
        $entityAnonymiser2->process($id)->shouldBeCalled();

        $connection->commit()->shouldBeCalled();
        $connection->rollBack()->shouldNotBeCalled();
        $logger->info(Argument::cetera())->shouldBeCalled();
        $logger->error(Argument::cetera())->shouldNotBeCalled();

        $this->process($id)->shouldReturn(1);
    }

    function it_should_rollback_anonymisation_process_for_every_quote_related_anonymisers_when_something_is_wrong(
        LoggerInterface $logger,
        BaseInterface $entityAnonymiser1,
        BaseInterface $entityAnonymiser2,
        AdapterInterface $connection
    ) {
        $id = 100;

        $connection->beginTransaction()->shouldBeCalled();

        $entityAnonymiser1->getEntityName()->shouldBeCalled()->willReturn('name1');
        $entityAnonymiser1->process($id)->shouldBeCalled();
        $entityAnonymiser2->getEntityName()->shouldBeCalled()->willReturn('name2');
        $entityAnonymiser2->process($id)->shouldBeCalled()->willThrow(Exception::class);

        $connection->commit()->shouldNotBeCalled();
        $connection->rollBack()->shouldBeCalled();

        $logger->info(Argument::cetera())->shouldNotBeCalled();
        $logger->error(
            Argument::type('string'),
            Argument::withKey('exception')
        )->shouldBeCalled();

        $this->process($id)->shouldReturn(0);
    }
}
