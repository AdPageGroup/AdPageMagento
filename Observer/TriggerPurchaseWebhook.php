<?php declare(strict_types=1);

namespace AdPage\GTM\Observer;

use AdPage\GTM\DataLayer\Event\PurchaseWebhookEvent;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class OrderSaveCommitAfter implements ObserverInterface
{
    private $webhookEvent;

    public function __construct(
        PurchaseWebhookEvent $webhookEvent
    ) {
        $this->webhookEvent = $webhookEvent;
    }

    public function execute(Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getOrder();

        if (!$order->dataHasChangedFor('total_paid') || $order->getGrandTotal() > $order->getTotalPaid()) {
            return;
        }

        try {
            $this->webhookEvent->purchase($order, []);
        } catch (\Exception $e) {
        }
    }
}