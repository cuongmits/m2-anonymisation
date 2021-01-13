<?php

namespace Cuongmits\Anonymisation\Anonymiser;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Psr\Log\LoggerInterface;
use Cuongmits\Anonymisation\Anonymiser\Entity\BaseInterface;

abstract class AnonymiserCollection implements AnonymiserCollectionInterface
{
    /** @var LoggerInterface */
    protected $logger;

    /** @var AdapterInterface */
    protected $connection;

    /** @var BaseInterface[] */
    protected $anonymisers;

    public function __construct(
        ResourceConnection $resourceConnection,
        LoggerInterface $logger,
        array $anonymisers = []
    ) {
        $this->connection = $resourceConnection->getConnection();
        $this->logger = $logger;
        $this->anonymisers = $anonymisers;
    }

    abstract public function process(int $id): int;
}
