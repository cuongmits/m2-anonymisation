<?php

namespace Cuongmits\Anonymisation\Anonymiser\Entity;

use Cuongmits\Anonymisation\AnonymisedValues;

class ShipmentTrack extends Base
{
    public function getTableName(): string
    {
        return 'sales_shipment_track';
    }

    public function getEntityName(): string
    {
        return 'Order Shipment Track';
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
            'track_number' => AnonymisedValues::DEFAULT_ANONYMISED_TRACKING_NUMBER,
            'tracking_url' => AnonymisedValues::DEFAULT_ANONYMISED_TRACKING_URL
        ];
    }
}
