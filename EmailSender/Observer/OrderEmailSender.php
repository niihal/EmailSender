<?php
/**
 * @category  Custom
 * @package   EmailSender
 * @author    Nihal Malik
 * @copyright Copyright (c) EmailSender (neehalmalik8@gmail.com)
 */

namespace Custom\EmailSender\Observer;

class OrderEmailSender implements \Magento\Framework\Event\ObserverInterface
{
    public function __construct(
        protected \Psr\Log\LoggerInterface $logger,
        protected \Custom\EmailSender\Helper\Data $data,
        protected \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender,
    ) {
        $this->logger = $logger;
        $this->data = $data;
        $this->orderSender = $orderSender;
    }

    /**
     * Execute method.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return \Custom\EmailSender\Observer\OrderEmailSender
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $enableEmailRestrict = $this->data->enableEmailRestrict();
        if(!$enableEmailRestrict){
            return $this;
        }
        // Check if the payment method is 'cashondelivery'
        $paymentMethod = $order->getPayment()->getMethod();

        // If payment method is  NOT 'cashondelivery', allow sending the email
        if (in_array( $paymentMethod, $this->data->enablePaymentMethods())) {
            if (!$order->getEmailSent()) {
                $this->orderSender->send($order, true);
            }
        }
        return $this;
    }
}
