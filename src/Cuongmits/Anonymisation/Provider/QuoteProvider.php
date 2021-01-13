<?php

namespace Cuongmits\Anonymisation\Provider;

use Magento\Framework\App\ResourceConnection;
use Cuongmits\Anonymisation\AnonymisedValues;
use Magento\Framework\DB\Adapter\AdapterInterface;

class QuoteProvider
{
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
     * Get quote ids which are not connected to any order and account
     *
     * @return array
     *
     * @throws \Zend_Db_Statement_Exception
     */
    public function getUnconnectedQuoteIds(): array
    {
        $retentionTime = $this->configProvider->getOtherRetentionTime();
        $cookieLifetime = $this->configProvider->getCookieLifetime();

        $query = $this->getUnconnectedQuoteIdsQuery();
        $res = $this->connection->query($query, [$retentionTime, $cookieLifetime, AnonymisedValues::DEFAULT_ANONYMISED_EMAIL]);

        $ids = [];
        foreach ($res->fetchAll() as $item) {
            $ids[] = $item['entity_id'];
        }

        return $ids;
    }

    /**
     * Get quote ids which are connected to orders but not connected to any account
     *
     * @return array
     *
     * @throws \Zend_Db_Statement_Exception
     */
    public function getConnectedQuoteIds(): array
    {
        $retentionTime = $this->configProvider->getGeneralRetentionTime();
        $cookieLifetime = $this->configProvider->getCookieLifetime();

        $query = $this->getConnectedQuoteIdsQuery();
        $res = $this->connection->query($query, [$retentionTime, $cookieLifetime, AnonymisedValues::DEFAULT_ANONYMISED_EMAIL]);

        $ids = [];
        foreach ($res->fetchAll() as $item) {
            $ids[] = $item['entity_id'];
        }

        return $ids;
    }

    private function getUnconnectedQuoteIdsQuery(): string
    {
        return "
            SELECT q.`entity_id`
            FROM `quote` AS q
            LEFT JOIN `sales_order` AS o
	            ON o.`quote_id` = q.`entity_id`
            WHERE
                q.`customer_id` IS NULL
                AND o.`entity_id` IS NULL
                AND q.`updated_at` < CURDATE() - INTERVAL ? DAY
                AND q.`updated_at` < CURDATE() - INTERVAL ? SECOND
                AND (q.`customer_email` IS NULL OR q.`customer_email` != ?);
        ";
    }

    private function getConnectedQuoteIdsQuery(): string
    {
        return "
            SELECT q.`entity_id`
            FROM `quote` AS q
            JOIN `sales_order` AS o
	            ON o.`quote_id` = q.`entity_id`  
            WHERE
                q.`customer_id` IS NULL
                AND q.`updated_at` < CURDATE() - INTERVAL ? DAY
                AND q.`updated_at` < CURDATE() - INTERVAL ? SECOND
                AND (q.`customer_email` IS NULL OR q.`customer_email` != ?);
        ";
    }
}
