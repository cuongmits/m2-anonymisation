<?php

namespace Cuongmits\Anonymisation\Anonymiser\Entity;

use Cuongmits\Anonymisation\AnonymisedValues;

class OrderPayment extends Base
{
    public function getTableName(): string
    {
        return 'sales_order_payment';
    }

    public function getEntityName(): string
    {
        return 'Order Payment';
    }

    public function getCondition(): string
    {
        return 'parent_id';
    }

    public function updatedDateExists(): bool
    {
        return false;
    }

    public function getAnonymisationColumns(): array
    {
        return [
            'cc_number_enc' => AnonymisedValues::DEFAULT_ANONYMISED_CC_NUMBER,
            'cc_owner' => AnonymisedValues::DEFAULT_ANONYMISED_CC_OWNER
        ];
    }
}
