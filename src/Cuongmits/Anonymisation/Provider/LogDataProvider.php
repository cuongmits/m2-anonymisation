<?php

namespace Cuongmits\Anonymisation\Provider;

use Magento\GiftCardAccount\Model\Giftcardaccount;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\Order;
use Cuongmits\CustomerAttribute;
use Cuongmits\SalesAttribute;
use Magento\Framework\Serialize\Serializer\Json;

class LogDataProvider
{
    private const CUSTOMER_LOYALTY_CARD = 'customer_loyalty_card';
    private const CUSTOMER_SAP_DEBITOR = 'customer_sap_debitor';
    private const CUSTOMER_TAX_VAT = 'customer_taxvat';
    private const CUSTOMER_TELEPHONE = 'customer_telephone';

    /**
     * Get order data without anonymisation information
     *
     * @param Order $order
     *
     * @return bool|string
     */
    public function getLogDataFromOrder(Order $order)
    {
        $data = $order->getData();
        unset($data[OrderInterface::CUSTOMER_DOB]);
        unset($data[OrderInterface::CUSTOMER_EMAIL]);
        unset($data[OrderInterface::CUSTOMER_GENDER]);
        unset($data[OrderInterface::CUSTOMER_FIRSTNAME]);
        unset($data[OrderInterface::CUSTOMER_MIDDLENAME]);
        unset($data[OrderInterface::CUSTOMER_LASTNAME]);
        unset($data[OrderInterface::CUSTOMER_PREFIX]);
        unset($data[OrderInterface::CUSTOMER_SUFFIX]);
        unset($data[OrderInterface::REMOTE_IP]);
        unset($data[OrderInterface::X_FORWARDED_FOR]);
        unset($data[OrderInterface::CUSTOMER_NOTE]);
        unset($data[Giftcardaccount::GIFT_CARDS]);
        unset($data[self::CUSTOMER_LOYALTY_CARD]);
        unset($data[self::CUSTOMER_SAP_DEBITOR]);
        unset($data[CustomerAttribute::SAP_DEBITOR]);
        unset($data[SalesAttribute::COMPUTOP_TRANS_ID]);
        unset($data[SalesAttribute::COMPUTOP_PAY_ID]);
        unset($data[SalesAttribute::COMPUTOP_PAYPAL_TRANSACTION_ID]);
        unset($data[self::CUSTOMER_TAX_VAT]);
        unset($data[self::CUSTOMER_TELEPHONE]);
        $serializer = new Json();

        return $serializer->serialize($data);
    }
}
