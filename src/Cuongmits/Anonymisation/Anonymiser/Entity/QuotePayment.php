<?php

namespace Cuongmits\Anonymisation\Anonymiser\Entity;

use Cuongmits\Anonymisation\AnonymisedValues;

class QuotePayment extends Base
{
    public function getTableName(): string
    {
        return 'quote_payment';
    }

    public function getEntityName(): string
    {
        return 'Quote Payment';
    }

    public function getCondition(): string
    {
        return 'quote_id';
    }

    public function updatedDateExists(): bool
    {
        return true;
    }

    public function getAnonymisationColumns(): array
    {
        return [
            'cc_number_enc' => AnonymisedValues::DEFAULT_ANONYMISED_CC_NUMBER,
            'cc_owner' => AnonymisedValues::DEFAULT_ANONYMISED_CC_OWNER
        ];
    }
}
