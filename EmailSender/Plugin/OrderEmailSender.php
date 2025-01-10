<?php
/**
 * @category  Custom
 * @package   EmailSender
 * @author    Nihal Malik
 * @copyright Copyright (c) EmailSender (neehalmalik8@gmail.com)
 */

namespace Custom\EmailSender\Plugin;

use Magento\Sales\Model\Order\Email\Sender\OrderSender;
use Magento\Sales\Model\Order;

class OrderEmailSender
{
    /**
     * Intercept the send method to disable sending the order email based on payment method.
     *
     * @param OrderSender $subject
     * @param \Closure $proceed
     * @param Order $order
     * @param bool $forceSync
     * @return void
     */

    public function __construct(
        protected \Psr\Log\LoggerInterface $logger,
        protected \Custom\EmailSender\Helper\Data $data
    ) {
        $this->logger = $logger;
        $this->data = $data;
    }

    public function aroundSend(
        OrderSender $subject,
        \Closure $proceed,
        Order $order,
        $forceSync = false
    ) {
        $enableEmailRestrict =$this->data->enableEmailRestrict();
        if(!$enableEmailRestrict){
            return $proceed($order, $forceSync);
        }
        // Check if the payment method is 'cashondelivery'
        $paymentMethod = $order->getPayment()->getMethod();

        // If payment method is 'other then selected', allow sending the email
        if (!in_array( $paymentMethod, $this->data->enablePaymentMethods()) || $forceSync) {
            $forceSync = false;
            return $proceed($order, $forceSync);
        }

        // If it's any other payment method, prevent sending the email
        return;
    }
}
