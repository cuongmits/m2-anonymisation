<?php

namespace Cuongmits\Anonymisation\Anonymiser\Entity;

use Cuongmits\Anonymisation\AnonymisedValues;

class Invoice extends Base
{
    public function getTableName(): string
    {
        return 'sales_invoice';
    }

    public function getEntityName(): string
    {
        return 'Order Invoice';
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
            'toom_invoice_id' => AnonymisedValues::DEFAULT_ANONYMISED_INVOICE_ID,
            'toom_filenet_id' => AnonymisedValues::DEFAULT_ANONYMISED_TOOM_FILENET_ID
        ];
    }
}
