<?php

namespace Cuongmits\Anonymisation\Anonymiser;

class OtherAnonymiser extends BaseAnonymiser
{
    public function run(): array
    {
        $quoteCounter = $orderCounter = 0;

        if (!$this->configProvider->isAnonymisationEnabled()) {
            $this->logger->info('Anonymisation feature is disabled at the moment.');

            return ['quote' => $quoteCounter, 'order' => $orderCounter];
        }

        $this->logger->info('Other guest data anonymisation started...');

        $quoteIds = $this->quoteProvider->getUnconnectedQuoteIds();
        foreach ($quoteIds as $quoteId) {
            $quoteCounter += $this->quoteRelatedAnonymiser->process($quoteId);
        }

        $orderIds = $this->orderProvider->getCancelledOrderIds();
        foreach ($orderIds as $orderId) {
            $orderCounter += $this->orderRelatedAnonymiser->process($orderId);
        }

        $this->logger->info(
            sprintf(
                'Other anonymisation finished: %d quote(s) and %d order(s) have been anonymised.',
                $quoteCounter,
                $orderCounter
            )
        );

        return ['quote' => $quoteCounter, 'order' => $orderCounter];
    }
}
