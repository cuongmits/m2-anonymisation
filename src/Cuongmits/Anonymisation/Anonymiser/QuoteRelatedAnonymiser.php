<?php

namespace Cuongmits\Anonymisation\Anonymiser;

use Exception;

class QuoteRelatedAnonymiser extends AnonymiserCollection
{
    public function process(int $id): int
    {
        try {
            $this->connection->beginTransaction();

            $result = [];
            foreach ($this->anonymisers as $anonymiser) {
                $result[$anonymiser->getEntityName()] = $anonymiser->process($id);
            }

            $this->connection->commit();
            $this->logger->info(
                sprintf('Related info to Quote %d has been anonymised.', $id),
                ['result' => $result]
            );
        } catch (Exception $exception) {
            $this->connection->rollBack();
            $this->logger->error(
                sprintf('Anonymisation for quote %d failed, rolling back completed.', $id),
                ['quoteId' => $id, 'exception' => $exception]
            );

            return 0;
        }

        return 1;
    }
}
