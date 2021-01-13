<?php

namespace spec\Cuongmits\Anonymisation\Anonymiser\Entity;

use Exception;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Cuongmits\Anonymisation\Anonymiser\Entity\Base;
use Cuongmits\Anonymisation\Anonymiser\Entity\Order;
use PhpSpec\ObjectBehavior;
use Cuongmits\Anonymisation\Exception\AnonymisationException;
use Zend_Db_Expr;

final class OrderSpec extends ObjectBehavior
{
    function let(ResourceConnection $resourceConnection, AdapterInterface $connection, LoggerInterface $logger)
    {
        $this->beConstructedWith($resourceConnection, $logger);
        $resourceConnection->getConnection()->willReturn($connection);
        $connection->quoteIdentifier(Argument::type('string'))->will(function ($args) {
            return $args[0] . '_identifier';
        });
        $connection->quoteInto(Argument::type('string'), Argument::any())->will(function($args) {
            return str_replace('?', $args[1] . '_value', $args[0]);
        });
        $connection->quoteInto(Argument::type('string'), [])->will(function($args) {
            return $args[0];
        });
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Order::class);
    }

    function it_is_instance_of_base_class()
    {
        $this->shouldBeAnInstanceOf(Base::class);
    }

    function it_should_process_a_single_order_with_all_bindings(
        AdapterInterface $connection,
        LoggerInterface $logger
    ) {
        $connection->update('sales_order', Argument::type('array'), ['entity_id = ?' => 234])
            ->willReturn(1)
            ->shouldBeCalled();
        $logger->info(Argument::cetera())->shouldBeCalled();
        $logger->error(Argument::cetera())->shouldNotBeCalled();

        $this->process(234)->shouldReturn(1);
    }

    function it_should_throw_an_exception_when_something_is_wrong(
        AdapterInterface $connection,
        LoggerInterface $logger
    ) {
        $connection->update('sales_order', Argument::type('array'), ['entity_id = ?' => 234])
            ->willThrow(Exception::class);
        $logger->info(Argument::cetera())->shouldNotBeCalled();
        $logger->error(Argument::type('string'), Argument::withKey('exception'))->shouldBeCalled();

        $this->shouldThrow(AnonymisationException::class)->duringProcess(234);
    }

    function it_should_create_binds_for_anonymised_columns()
    {
        $subject = $this->buildBindExpressions(['foo' => 'xxxxx', 'gender' => 2, 'customer_email' => 'x@x.invalid']);

        $subject->shouldHaveCount(6);
        $subject->offsetGet('foo')->shouldBeLike(new Zend_Db_Expr('IF(foo_identifier IS NULL, NULL, xxxxx_value)'));
        $subject->offsetGet('gender')->shouldBeLike(new Zend_Db_Expr('IF(gender_identifier IS NULL, NULL, 2_value)'));
        $subject->offsetGet('customer_email')->shouldBeLike(new Zend_Db_Expr('x@x.invalid_value'));
        $subject->offsetGet('updated_at')->shouldBeLike(new Zend_Db_Expr('updated_at_identifier'));
        $subject->offsetGet('increment_id')->shouldBeLike(new Zend_Db_Expr('CONCAT(SUBSTRING_INDEX(increment_id_identifier, "-", 2), "-", entity_id_identifier)'));
        $subject->offsetGet('toom_group_id')->shouldBeLike(new Zend_Db_Expr('IF(toom_group_id_identifier IS NULL, NULL, entity_id_identifier)'));
    }

    function it_should_return_correct_table_name()
    {
        $this->getTableName()->shouldReturn('sales_order');
    }

    function it_should_return_correct_entity_name()
    {
        $this->getEntityName()->shouldReturn('Order');
    }

    function it_should_return_correct_condition()
    {
        $this->getCondition()->shouldReturn('entity_id');
    }

    function it_should_return_correct_update_date_exists_status()
    {
        $this->updatedDateExists()->shouldReturn(true);
    }

    function it_should_return_correct_anonymisation_columns()
    {
        $this->getAnonymisationColumns()->shouldBeArray();
    }

    function it_should_return_correct_markup()
    {
        $this->getMarkup()->shouldReturn('customer_email');
    }
}
