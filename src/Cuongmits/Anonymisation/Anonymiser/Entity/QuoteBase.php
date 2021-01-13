<?php
namespace Cuongmits\Anonymisation\Anonymiser\Entity;

use Magento\Quote\Api\Data\CartInterface;
use Cuongmits\SalesAttribute;
use Zend_Db_Expr;

abstract class QuoteBase extends Base
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

        $parts[CartInterface::KEY_RESERVED_ORDER_ID] = new Zend_Db_Expr(
            sprintf(
                'IF(%1$s IS NULL, NULL, CONCAT(SUBSTRING_INDEX(%1$s, "-", 2), "-", %2$s))',
                $this->connection->quoteIdentifier(CartInterface::KEY_RESERVED_ORDER_ID),
                $this->connection->quoteIdentifier(CartInterface::KEY_ENTITY_ID)
            )
        );
        $parts[SalesAttribute::GROUP_ID] = new Zend_Db_Expr(
            sprintf(
                'IF(%s IS NULL, NULL, %s)',
                $this->connection->quoteIdentifier(SalesAttribute::GROUP_ID),
                $this->connection->quoteIdentifier(CartInterface::KEY_ENTITY_ID)
            )
        );

        return $parts;
    }
}
