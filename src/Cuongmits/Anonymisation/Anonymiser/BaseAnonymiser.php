<?php
namespace Cuongmits\Anonymisation\Anonymiser;

use Psr\Log\LoggerInterface;
use Cuongmits\Anonymisation\Provider\ConfigProvider;
use Cuongmits\Anonymisation\Provider\OrderProvider;
use Cuongmits\Anonymisation\Provider\QuoteProvider;

abstract class BaseAnonymiser implements BaseAnonymiserInterface
{
    /** @var ConfigProvider */
    protected $configProvider;

    /** @var LoggerInterface */
    protected $logger;

    /** @var QuoteProvider */
    protected $quoteProvider;

    /** @var OrderProvider */
    protected $orderProvider;

    /** @var QuoteRelatedAnonymiser */
    protected $quoteRelatedAnonymiser;

    /** @var OrderRelatedAnonymiser */
    protected $orderRelatedAnonymiser;

    public function __construct(
        ConfigProvider $configProvider,
        LoggerInterface $logger,
        QuoteProvider $quoteProvider,
        OrderProvider $orderProvider,
        QuoteRelatedAnonymiser $quoteRelatedAnonymiser,
        OrderRelatedAnonymiser $orderRelatedAnonymiser
    ) {
        $this->configProvider = $configProvider;
        $this->logger = $logger;
        $this->quoteProvider = $quoteProvider;
        $this->orderProvider = $orderProvider;
        $this->quoteRelatedAnonymiser = $quoteRelatedAnonymiser;
        $this->orderRelatedAnonymiser = $orderRelatedAnonymiser;
    }
}
