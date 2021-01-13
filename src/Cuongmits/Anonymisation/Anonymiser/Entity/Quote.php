<?php

namespace Cuongmits\Anonymisation\Anonymiser\Entity;

use Cuongmits\Anonymisation\AnonymisedValues;
use Cuongmits\SalesAttribute;

class Quote extends QuoteBase
{
    public const TABLE_NAME = 'quote';

    public function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    public function getEntityName(): string
    {
        return 'Quote';
    }

    public function getCondition(): string
    {
        return 'entity_id';
    }

    public function updatedDateExists(): bool
    {
        return true;
    }

    public function getAnonymisationColumns(): array
    {
        return [
            'customer_email' => AnonymisedValues::DEFAULT_ANONYMISED_EMAIL,
            'customer_prefix' => AnonymisedValues::DEFAULT_ANONYMISED_PREFIX,
            'customer_firstname' => AnonymisedValues::DEFAULT_ANONYMISED_FIRSTNAME,
            'customer_middlename' => AnonymisedValues::DEFAULT_ANONYMISED_MIDDLENAME,
            'customer_lastname' => AnonymisedValues::DEFAULT_ANONYMISED_LASTNAME,
            'customer_suffix' => AnonymisedValues::DEFAULT_ANONYMISED_SUFFIX,
            'customer_dob' => AnonymisedValues::DEFAULT_ANONYMISED_DATE,
            'remote_ip' => AnonymisedValues::DEFAULT_ANONYMISED_IP_ADDRESS,
            'customer_taxvat' => AnonymisedValues::DEFAULT_ANONYMISED_TAXVAT,
            'customer_gender' => AnonymisedValues::DEFAULT_ANONYMISED_GENDER,
            'customer_note' => AnonymisedValues::DEFAULT_ANONYMISED_NOTE,
            'ext_shipping_info' => AnonymisedValues::DEFAULT_ANONYMISED_EX_SHIPPING_INFO,
            'gift_cards' => AnonymisedValues::DEFAULT_ANONYMISED_GIFT_CARD,
            SalesAttribute::LOYALTY_CARD_NUMBER => AnonymisedValues::DEFAULT_ANONYMISED_LOYALTY_CARD,
            SalesAttribute::COMPUTOP_TRANS_ID => AnonymisedValues::DEFAULT_ANONYMISED_TRANS_ID,
            SalesAttribute::COMPUTOP_PAY_ID => AnonymisedValues::DEFAULT_ANONYMISED_PAY_ID,
            SalesAttribute::COMPUTOP_PAYPAL_TRANSACTION_ID => AnonymisedValues::DEFAULT_ANONYMISED_PAYPAL_TRANSACTION_ID
        ];
    }

    public function getMarkup(): string
    {
        return 'customer_email';
    }
}
