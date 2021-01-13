<?php

namespace Cuongmits\Anonymisation\Anonymiser\Entity;

use Cuongmits\Anonymisation\AnonymisedValues;

class OrderAddress extends Base
{
    public function getTableName(): string
    {
        return 'sales_order_address';
    }

    public function getEntityName(): string
    {
        return 'Order Address';
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
            'email' => AnonymisedValues::DEFAULT_ANONYMISED_EMAIL,
            'city' => AnonymisedValues::DEFAULT_ANONYMISED_CITY,
            'company' => AnonymisedValues::DEFAULT_ANONYMISED_COMPANY,
            'country_id' => AnonymisedValues::DEFAULT_ANONYMISED_COUNTRY,
            'fax' => AnonymisedValues::DEFAULT_ANONYMISED_FAX,
            'firstname' => AnonymisedValues::DEFAULT_ANONYMISED_FIRSTNAME,
            'lastname' => AnonymisedValues::DEFAULT_ANONYMISED_LASTNAME,
            'middlename' => AnonymisedValues::DEFAULT_ANONYMISED_MIDDLENAME,
            'prefix' => AnonymisedValues::DEFAULT_ANONYMISED_PREFIX,
            'suffix' => AnonymisedValues::DEFAULT_ANONYMISED_SUFFIX,
            'telephone' => AnonymisedValues::DEFAULT_ANONYMISED_TELEPHONE,
            'region_id' => AnonymisedValues::DEFAULT_ANONYMISED_REGION_ID,
            'region' => AnonymisedValues::DEFAULT_ANONYMISED_REGION,
            'street' => AnonymisedValues::DEFAULT_ANONYMISED_STREET,
            'vat_id' => AnonymisedValues::DEFAULT_ANONYMISED_VAT_ID,
            'postcode' => AnonymisedValues::DEFAULT_ANONYMISED_POSTCODE,
            'salutation' => AnonymisedValues::DEFAULT_ANONYMISED_SALUTATION
        ];
    }
}
