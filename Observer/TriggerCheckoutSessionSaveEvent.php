<?php

declare(strict_types=1);

namespace AdPage\GTM\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Psr\Log\LoggerInterface;
use Magento\Checkout\Model\Session as CheckoutSession;

class TriggerCheckoutSessionSaveEvent implements ObserverInterface
{
    private CheckoutSession $checkoutSession;
    private OrderRepositoryInterface $orderRepository;
    private LoggerInterface $logger;


    public function __construct(
        CheckoutSession $checkoutSession,
        LoggerInterface $logger,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->checkoutSession = $checkoutSession;
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        /** @var OrderInterface $order */
        $order = $observer->getData('order');

        $customData = $this->checkoutSession->getData('trytagging_marketing');

        $this->logger->critical('TriggerCheckoutSessionSaveEvent::execute(): has changed ', var_export($customData, true));

        if ($customData) {
            $this->logger->critical('TriggerCheckoutSessionSaveEvent::execute(): has changed2 ', var_export($customData, true));
            $order->setData('trytagging_marketing', $customData);
            $this->orderRepository->save($order);
            $this->checkoutSession->unsetData('trytagging_marketing');
        }
    }
}
