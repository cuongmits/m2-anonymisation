<?php

namespace Cuongmits\Anonymisation\Anonymiser\Entity;

use Cuongmits\Anonymisation\AnonymisedValues;

class QuoteAddress extends Base
{
    public function getTableName(): string
    {
        return 'quote_address';
    }

    public function getEntityName(): string
    {
        return 'Quote Address';
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
            'email' => AnonymisedValues::DEFAULT_ANONYMISED_EMAIL,
            'prefix' => AnonymisedValues::DEFAULT_ANONYMISED_PREFIX,
            'firstname' => AnonymisedValues::DEFAULT_ANONYMISED_FIRSTNAME,
            'middlename' => AnonymisedValues::DEFAULT_ANONYMISED_MIDDLENAME,
            'lastname' => AnonymisedValues::DEFAULT_ANONYMISED_LASTNAME,
            'suffix' => AnonymisedValues::DEFAULT_ANONYMISED_SUFFIX,
            'company' => AnonymisedValues::DEFAULT_ANONYMISED_COMPANY,
            'street' => AnonymisedValues::DEFAULT_ANONYMISED_STREET,
            'city' => AnonymisedValues::DEFAULT_ANONYMISED_CITY,
            'postcode' => AnonymisedValues::DEFAULT_ANONYMISED_POSTCODE,
            'country_id' => AnonymisedValues::DEFAULT_ANONYMISED_COUNTRY,
            'telephone' => AnonymisedValues::DEFAULT_ANONYMISED_TELEPHONE,
            'fax' => AnonymisedValues::DEFAULT_ANONYMISED_TELEPHONE,
            'region_id' => AnonymisedValues::DEFAULT_ANONYMISED_REGION_ID,
            'region' => AnonymisedValues::DEFAULT_ANONYMISED_REGION,
            'vat_id' => AnonymisedValues::DEFAULT_ANONYMISED_VAT_ID,
            'salutation' => AnonymisedValues::DEFAULT_ANONYMISED_SALUTATION
        ];
    }
}
