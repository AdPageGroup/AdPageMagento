<?php

declare(strict_types=1);

namespace AdPage\GTM\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Checkout\Model\Session as CheckoutSession;

class TriggerCheckoutSessionSaveEvent implements ObserverInterface
{
    private CheckoutSession $checkoutSession;
    private OrderRepositoryInterface $orderRepository;


    public function __construct(
        CheckoutSession $checkoutSession,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->checkoutSession = $checkoutSession;
    }

    public function execute(Observer $observer)
    {
        /** @var OrderInterface $order */
        $order = $observer->getData('order');

        $customData = $this->checkoutSession->getData('trytagging_marketing');

        if ($customData) {
            $order->setData('trytagging_marketing', $customData);
            $this->orderRepository->save($order);
            $this->checkoutSession->unsetData('trytagging_marketing');
        }
    }
}
