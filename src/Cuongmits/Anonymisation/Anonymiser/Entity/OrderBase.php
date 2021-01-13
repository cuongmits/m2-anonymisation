<?php
namespace Cuongmits\Anonymisation\Anonymiser\Entity;

use Magento\Sales\Api\Data\OrderInterface;
use Cuongmits\SalesAttribute;
use Zend_Db_Expr;

abstract class OrderBase extends Base
{
    /**
     * Build expressions used as parts of a SQL SET clause
     *
     * @param array $anonymiseColumns New values of columns, keyed by column name
     *
     * @return array|Zend_Db_Expr[]
     */
    public function buildBindExpressions(array $anonymiseColumns): array
    {
        $parts = parent::buildBindExpressions($anonymiseColumns);

        $parts[OrderInterface::INCREMENT_ID] = new Zend_Db_Expr(
            sprintf(
                'CONCAT(SUBSTRING_INDEX(%s, "-", 2), "-", %s)',
                $this->connection->quoteIdentifier(OrderInterface::INCREMENT_ID),
                $this->connection->quoteIdentifier(OrderInterface::ENTITY_ID)
            )
        );
        $parts[SalesAttribute::GROUP_ID] = new Zend_Db_Expr(
            sprintf(
                'IF(%s IS NULL, NULL, %s)',
                $this->connection->quoteIdentifier(SalesAttribute::GROUP_ID),
                $this->connection->quoteIdentifier(OrderInterface::ENTITY_ID)
            )
        );

        return $parts;
    }
}
