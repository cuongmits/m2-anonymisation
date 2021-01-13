<?php

namespace Cuongmits\Anonymisation\Anonymiser\Entity;

use Psr\Log\LoggerInterface;
use Exception;
use Cuongmits\Anonymisation\Exception\AnonymisationException;
use Magento\Sales\Model\ResourceModel\GridPool;

class Grids implements BaseInterface
{
    /** @var GridPool */
    private $gridPool;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger, GridPool $gridPool)
    {
        $this->logger = $logger;
        $this->gridPool = $gridPool;
    }

    public function process(int $id): int
    {
        try {
            $this->gridPool->refreshByOrderId($id);
            $this->logger->info(
                sprintf('%s with order_id=%s has been refreshed.', $this->getEntityName(), $id),
                ['count' => 1]
            );
        } catch (Exception $e) {
            $this->logger->error(
                sprintf('Sub-anonymisation for %s with order id %s failed.', $this->getEntityName(), $id),
                ['exception' => $e]
            );

            throw new AnonymisationException(__('%1 with order id %2 anonymisation failed.', $this->getEntityName(), $id));
        }

        return 1;
    }

    public function getEntityName(): string
    {
        return 'Order-Related Grids';
    }
}
