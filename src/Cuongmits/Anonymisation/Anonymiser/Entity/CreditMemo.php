<?php

namespace Cuongmits\Anonymisation\Anonymiser\Entity;

use Cuongmits\Anonymisation\AnonymisedValues;
use Cuongmits\SalesAttribute;
use Magento\Sales\Api\Data\CreditmemoInterface;

class CreditMemo extends Base
{
    public function getTableName(): string
    {
        return 'sales_creditmemo';
    }

    public function getEntityName(): string
    {
        return 'Credit Memo';
    }

    public function getCondition(): string
    {
        return 'order_id';
    }

    public function updatedDateExists(): bool
    {
        return true;
    }

    public function getAnonymisationColumns(): array
    {
        return [
            SalesAttribute::TOOM_RETURN_ID => AnonymisedValues::DEFAULT_ANONYMISED_TOOM_RETURN_ID,
            SalesAttribute::TOOM_FILENET_ID => AnonymisedValues::DEFAULT_ANONYMISED_TOOM_FILENET_ID,
            SalesAttribute::TOOM_COMPENSATION_ID => AnonymisedValues::DEFAULT_ANONYMISED_TOOM_COMPENSATION_ID,
            CreditmemoInterface::TRANSACTION_ID => AnonymisedValues::DEFAULT_ANONYMISED_TOOM_TRANSACTION_ID
        ];
    }
}
