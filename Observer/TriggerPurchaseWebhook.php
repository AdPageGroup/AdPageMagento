<?php declare(strict_types=1);

namespace AdPage\GTM\Observer;

use AdPage\GTM\DataLayer\Event\PurchaseWebhookEvent;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Psr\Log\LoggerInterface;
use AdPage\GTM\Logger\Debugger;

class TriggerPurchaseWebhook implements ObserverInterface
{
    private $webhookEvent;
    private $debugger;
    private $logger;

    public function __construct(
        PurchaseWebhookEvent $webhookEvent,
        Debugger $debugger,
        LoggerInterface $logger
    ) {
        $this->webhookEvent = $webhookEvent;
        $this->debugger = $debugger;
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        /** @var OrderInterface $order */
        $order = $observer->getData('order');

        $this->logger->critical('TriggerPurchaseWebhook::execute(): has changed ' . $order->dataHasChangedFor('total_paid'));
        $this->logger->critical('TriggerPurchaseWebhook::execute(): has grand total ' . $order->getGrandTotal());
        $this->logger->critical('TriggerPurchaseWebhook::execute(): total paid ' . $order->getTotalPaid());

        if (!$order->dataHasChangedFor('total_paid') || $order->getGrandTotal() > $order->getTotalPaid()) {
            return;
        }

        try {
            $this->webhookEvent->purchase($order, []);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}