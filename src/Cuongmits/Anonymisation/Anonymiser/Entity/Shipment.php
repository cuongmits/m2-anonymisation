<?php

namespace Cuongmits\Anonymisation\Anonymiser\Entity;

use Cuongmits\Anonymisation\AnonymisedValues;

class Shipment extends Base
{
    public function getTableName(): string
    {
        return 'sales_shipment';
    }

    public function getEntityName(): string
    {
        return 'Order Shipment';
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
            'customer_note' => AnonymisedValues::DEFAULT_ANONYMISED_NOTE
        ];
    }
}
