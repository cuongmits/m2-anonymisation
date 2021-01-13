<?php

namespace Cuongmits\Anonymisation\Provider;

use Magento\Framework\App\ResourceConnection;
use Cuongmits\Anonymisation\AnonymisedValues;
use Magento\Framework\DB\Adapter\AdapterInterface;

class OrderProvider
{
    public const CANCELLED_ORDER_STATE = 'canceled';

    /** @var ConfigProvider */
    private $configProvider;

    /** @var AdapterInterface */
    protected $connection;

    public function __construct(ConfigProvider $configProvider, ResourceConnection $resourceConnection)
    {
        $this->configProvider = $configProvider;
        $this->connection = $resourceConnection->getConnection();
    }

    /**
     * Get cancelled order ids, which is not connected to any customer account
     *
     * @return array
     *
     * @throws \Zend_Db_Statement_Exception
     */
    public function getCancelledOrderIds(): array
    {
        $retentionTime = $this->configProvider->getOtherRetentionTime();

        $query = $this->getCancelledOrderIdsQuery();
        $res = $this->connection->query($query, [self::CANCELLED_ORDER_STATE, $retentionTime, AnonymisedValues::DEFAULT_ANONYMISED_EMAIL]);

        $ids = [];
        foreach ($res->fetchAll() as $item) {
            $ids[] = $item['entity_id'];
        }

        return $ids;
    }

    /**
     * Get non-cancelled order ids, which is not connected to any customer account
     *
     * @return array
     *
     * @throws \Zend_Db_Statement_Exception
     */
    public function getNonCancelledOrderIds(): array
    {
        $retentionTime = $this->configProvider->getGeneralRetentionTime();

        $query = $this->getNonCancelledOrderIdsQuery();
        $res = $this->connection->query($query, [self::CANCELLED_ORDER_STATE, $retentionTime, AnonymisedValues::DEFAULT_ANONYMISED_EMAIL]);

        $ids = [];
        foreach ($res->fetchAll() as $item) {
            $ids[] = $item['entity_id'];
        }

        return $ids;
    }

    private function getCancelledOrderIdsQuery(): string
    {
        return "
            SELECT o.`entity_id`
            FROM `sales_order` AS o
            WHERE
	            o.`customer_id` IS NULL
	            AND o.`state` = ?
	            AND o.`updated_at` < CURDATE() - INTERVAL ? DAY
	            AND (o.`customer_email` IS NULL OR o.`customer_email` != ?);
        ";
    }

    private function getNonCancelledOrderIdsQuery(): string
    {
        return "
            SELECT o.`entity_id`
            FROM `sales_order` AS o
            WHERE
	            o.`customer_id` IS NULL
	            AND o.`state` != ?
	            AND o.`updated_at` < CURDATE() - INTERVAL ? DAY
	            AND (o.`customer_email` IS NULL OR o.`customer_email` != ?);
        ";
    }
}
