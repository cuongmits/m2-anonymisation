<?php

namespace Cuongmits\Anonymisation\Anonymiser;

class GeneralAnonymiser extends BaseAnonymiser
{
    public function run(): array
    {
        $quoteCounter = $orderCounter = 0;

        if (!$this->configProvider->isAnonymisationEnabled()) {
            $this->logger->info('Anonymisation feature is disabled at the moment.');

            return ['quote' => $quoteCounter, 'order' => $orderCounter];
        }

        $this->logger->info('General guest data anonymisation started...');

        $quoteIds = $this->quoteProvider->getConnectedQuoteIds();
        foreach ($quoteIds as $quoteId) {
            $quoteCounter += $this->quoteRelatedAnonymiser->process($quoteId);
        }

        $orderIds = $this->orderProvider->getNonCancelledOrderIds();
        foreach ($orderIds as $orderId) {
            $orderCounter += $this->orderRelatedAnonymiser->process($orderId);
        }

        $this->logger->info(
            sprintf(
                'General anonymisation finished: %d quote(s) and %d order(s) have been anonymised.',
                $quoteCounter,
                $orderCounter
            )
        );

        return ['quote' => $quoteCounter, 'order' => $orderCounter];
    }
}
