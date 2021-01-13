<?php

namespace Cuongmits\Anonymisation\Anonymiser\Entity;

use Cuongmits\Anonymisation\AnonymisedValues;
use Cuongmits\SalesAttribute;

class Order extends OrderBase
{
    public const TABLE_NAME = 'sales_order';

    public function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    public function getEntityName(): string
    {
        return 'Order';
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
            'customer_dob' => AnonymisedValues::DEFAULT_ANONYMISED_DATE,
            'customer_email' => AnonymisedValues::DEFAULT_ANONYMISED_EMAIL,
            'customer_firstname' => AnonymisedValues::DEFAULT_ANONYMISED_FIRSTNAME,
            'customer_gender' => AnonymisedValues::DEFAULT_ANONYMISED_GENDER,
            'customer_lastname' => AnonymisedValues::DEFAULT_ANONYMISED_LASTNAME,
            'customer_middlename' => AnonymisedValues::DEFAULT_ANONYMISED_MIDDLENAME,
            'customer_prefix' => AnonymisedValues::DEFAULT_ANONYMISED_PREFIX,
            'customer_suffix' => AnonymisedValues::DEFAULT_ANONYMISED_SUFFIX,
            'remote_ip' => AnonymisedValues::DEFAULT_ANONYMISED_IP_ADDRESS,
            'x_forwarded_for' => AnonymisedValues::DEFAULT_ANONYMISED_X_FORWARDED_FOR,
            'customer_note' => AnonymisedValues::DEFAULT_ANONYMISED_NOTE,
            'customer_taxvat' => AnonymisedValues::DEFAULT_ANONYMISED_TAXVAT,
            'gift_cards' => AnonymisedValues::DEFAULT_ANONYMISED_GIFT_CARD,
            SalesAttribute::LOYALTY_CARD_NUMBER => AnonymisedValues::DEFAULT_ANONYMISED_LOYALTY_CARD,
            SalesAttribute::SAP_DEBITOR => AnonymisedValues::DEFAULT_ANONYMISED_SAP_DEBITOR,
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
